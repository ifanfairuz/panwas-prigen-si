<?php

namespace App\Actions\Dropbox;

use App\Models\Config;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Http;
use Spatie\Dropbox\TokenProvider as Provider;

class TokenProvider implements Provider
{

    protected $key;
    protected $secret;

    /**
     * @var \App\Models\Config
     */
    protected $token;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->token = Config::getConfig('dropbox_token');
    }

    public function getTokenFromCode()
    {
        $this->token->refresh();
        if ($this->token->value == '') {
            throw new Exception("dropbox token not found.");
        }

        $code = $this->token->asObject()->auth_code;
        $response = Http::withBasicAuth($this->key, $this->secret)
            ->asForm()
            ->acceptJson()
            ->post('https://api.dropboxapi.com/oauth2/token', [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => route('administration.config.dropbox.granted'),
            ]);
        if ($response->successful()) {
            $data = json_decode($response->body(), true);
            $data['expires_on'] = now()->addSeconds($data['expires_in'])->subSeconds(20)->format(Carbon::ISO8601);
            $this->token->mergeJson($data);
            return $data['access_token'];
        } else {
            throw $response->toException();
            return '';
        }
    }

    public function getToken(): string
    {
        $this->token->refresh();
        if ($this->token->value == '') {
            throw new Exception("dropbox token not found.");
        }

        $data = $this->token->asObject();
        if (@$data->access_token && $data->access_token != '') {
            if (now()->isBefore(Carbon::createFromFormat(Carbon::ISO8601, $data->expires_on))) {
                return $data->access_token;
            }

            $this->refreshToken();
            return $this->token->asObject()->access_token;
        }

        return '';
    }

    public function refreshToken()
    {
        $this->token->refresh();
        if ($this->token->value == '') {
            throw new Exception("dropbox token not found.");
        }
        $refresh_token = @$this->token->asObject()->refresh_token;
        if ($refresh_token == '') {
            throw new Exception("dropbox token not found.");
        }

        $response = Http::withBasicAuth($this->key, $this->secret)
            ->asForm()
            ->acceptJson()
            ->post('https://api.dropboxapi.com/oauth2/token', [
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token',
                'redirect_uri' => route('administration.config.dropbox.granted'),
            ]);
        if ($response->successful()) {
            $data = json_decode($response->body(), true);
            $data['expires_on'] = now()->addSeconds($data['expires_in'])->subSeconds(20)->toISOString();
            $this->token->mergeJson($data);
        } else {
            throw $response->toException();
        }
    }

    /**
     * get access
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public static function getAccess($request)
    {
        $config = config('filesystems.disks.dropbox');
        $uri = new Uri("https://www.dropbox.com/oauth2/authorize");
        $uri = Uri::withQueryValues($uri, [
            'client_id' => $config['key'],
            'token_access_type' => 'offline',
            'response_type' => 'code',
            'redirect_uri' => route('administration.config.dropbox.granted'),
        ]);
        return redirect()->away($uri);
    }

    /**
     * save auth_code
     * @param string $code
     * @return bool
     */
    public static function grantAccess($code)
    {
        try {
            $config = config('filesystems.disks.dropbox');
            $token = Config::getConfig('dropbox_token');
            $token->setJson('auth_code', $code);
            $provider = new self($config['key'], $config['secret']);
            $provider->getTokenFromCode();
            return true;
        } catch (Exception $exception) {
            dd($exception);
            return false;
        }
    }
}

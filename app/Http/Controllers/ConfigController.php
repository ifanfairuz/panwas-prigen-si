<?php

namespace App\Http\Controllers;

use App\Actions\Dropbox\TokenProvider;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ConfigController extends Controller
{
    
    /**
     * config page
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $types = collect([
            'tugas_panwas' => "S-Tugas TTD Ketua",
            'keluar_panwas' => "S-Keluar TTD Ketua",
            'tugas_sekretariat' => "S-Keluar Kesekretariatan",
            'spd' => "Surat Perjalanan Dinas",
        ]);
        return Inertia::render('Config/Index', [
            'dropbox' => [
                'token' => Config::getConfig('dropbox_token')->asArray()
            ],
            'templates' => $types->map(fn ($label, $key) => (
                Storage::exists("templates/{$key}.docx") ? "templates/{$key}.docx" : null
            ))
        ]);
    }

    /**
     * config template_upload
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function template_upload(Request $request)
    {
        try {
            Validator::make($request->all(), [
                'type' => ['required', 'string', 'max:255'],
                'file' => ['required', 'file', 'max:10000', 'mimes:docx'],
            ])->validate();
            
            $type = $request->input('type');
            $file = $request->file('file');
            $file->storeAs('templates', "{$type}.{$file->getClientOriginalExtension()}", 'local');

            return response()->injectRedirectBanner(back(), 'Surat diupload!');
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            dd($exception);
            return response()->injectErrorBanner($this->index($request), "Gagal - {$exception->getMessage()}");
        }
    }

    /**
     * get access dropbox
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function dropbox_getaccess(Request $request)
    {
        return TokenProvider::getAccess($request);
    }
    
    /**
     * get access dropbox
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function dropbox_granted(Request $request)
    {
        $code = $request->query('code');
        if ($code && $code != '') {
            $granted = TokenProvider::grantAccess($code);
            if ($granted) return response()->redirectWithBanner('administration.config.index', 'Koneksi dropbox berhasil!');
        }

        return response()->redirectWithBanner('administration.config.index', 'Koneksi dropbox gagal!', 'danger');
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\Dropbox\TokenProvider;
use App\Models\Config;
use Illuminate\Http\Request;
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
        return Inertia::render('Config/Index', [
            'dropbox' => [
                'token' => Config::getConfig('dropbox_token')->asArray()
            ]
        ]);
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

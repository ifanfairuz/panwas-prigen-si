<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class DropboxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config) {
            $token_provider = new \App\Actions\Dropbox\TokenProvider($config['key'], $config['secret']);
            $client = new \Spatie\Dropbox\Client($token_provider);
  
            return new \League\Flysystem\Filesystem(new \Spatie\FlysystemDropbox\DropboxAdapter($client));
        });
    }
}

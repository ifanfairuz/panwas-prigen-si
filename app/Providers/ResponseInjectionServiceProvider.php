<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class ResponseInjectionServiceProvider extends ServiceProvider
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
        Response::macro('renderErrorBanner', function ($component, $message, $props = []) {
            return Inertia::render($component, $props)
                ->with('jetstream.flash.banner', $message)
                ->with('jetstream.flash.bannerStyle', "danger")
                ->with('errors', ['default' => 'errorBanner']);
        });

        Response::macro('redirectWithBanner', function ($route, $message, $style = 'success') {
            return redirect()->route($route)
                    ->with('flash.banner', $message)
                    ->with('flash.bannerStyle', $style);
        });
    }
}

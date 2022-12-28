<?php

namespace App\Providers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

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
        $injectErrorBanner = fn(InertiaResponse $response, $message) => $response
            ->with('jetstream.flash.banner', $message)
            ->with('jetstream.flash.bannerStyle', "danger")
            ->with('errors', ['default' => 'errorBanner']);
        $injectRedirectBanner = fn(RedirectResponse $response, $message, $style = 'success') => $response
            ->with('flash.banner', $message)
            ->with('flash.bannerStyle', $style);

        Response::macro('injectErrorBanner', $injectErrorBanner);
        Response::macro('renderErrorBanner', fn ($component, $message, $props = []) => $injectErrorBanner(Inertia::render($component, $props), $message));
        Response::macro('injectRedirectBanner', $injectRedirectBanner);
        Response::macro('redirectWithBanner', fn ($route, $message, $style = 'success') => $injectRedirectBanner(redirect()->route($route), $message, $style));
    }
}

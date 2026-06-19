<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        // Hanya force https jika request asli dari https (misal via ngrok)
        if (config('app.env') == 'local' && request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        } elseif (config('app.env') == 'local') {
            URL::forceScheme('http');
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): string => Blade::render('<div style="text-align: center; margin-top: 16px;">
                <a href="{{ route(\'auth.forgot-password\') }}" style="color: #00c357; font-size: 14px; text-decoration: none;">
                    Lupa Password?
                </a>
            </div>'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => '
                <style>
                    .fi-simple-header-heading {
                        display: none !important;
                    }
                    nav.fi-topbar {
                        background: #00c357 !important;
                    }
                    .fi-topbar .fi-logo {
                        color: #ffffff !important;
                    }
                    .fi-sidebar-nav a, .fi-sidebar-nav button, .fi-sidebar-item-label,
                    .fi-sidebar-item-label a, .fi-sidebar-item a {
                        color: #00c357 !important;
                    }
                </style>
            ',
        );
    }
}

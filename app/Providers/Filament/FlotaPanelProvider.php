<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CuponResource;
use App\Filament\Resources\BosemResource;
use App\Filament\Resources\ElementosambResource;
use App\Filament\Resources\HerramientasambResource;
use App\Filament\Resources\AmbulanciaResource;
use App\Filament\Resources\ListaChequeoResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class FlotaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('flota')
            ->path('flota')
            ->sidebarWidth('15rem')
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->login()
            ->colors([
                'primary' => Color::hex('#206bc4'),
                'danger' => Color::Red,
                'red' => Color::hex('#ff0303'),
                'amarillo' => Color::hex('#fbff03'),
                'verde' => Color::hex('#03ff1c'),
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'orange' => Color::Orange,
                'sidebar' => Color::hex('#fff'),
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()->imageProvider(
                    MyImages::make()
                        ->directory('images/backgrounds')
                ),
            ])
            ->brandLogo(asset('images/logo222.svg'))
            ->favicon(asset('images/logocheques.svg'))
            ->discoverResources(in: app_path('Filament/Flota/Resources'), for: 'App\\Filament\\Flota\\Resources')
            ->discoverPages(in: app_path('Filament/Flota/Pages'), for: 'App\\Filament\\Flota\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->resources([
                ListaChequeoResource::class,
                BosemResource::class,
                ElementosambResource::class,
                HerramientasambResource::class,
                AmbulanciaResource::class,
                CuponResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Flota/Widgets'), for: 'App\\Filament\\Flota\\Widgets')
            ->widgets([

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

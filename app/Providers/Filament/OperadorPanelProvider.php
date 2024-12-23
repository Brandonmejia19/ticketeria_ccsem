<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\CasoResource;
use App\Filament\Resources\CasosListadoResource;
use App\Filament\Resources\LlamadasResource;
use App\Filament\Resources\UserResource;
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
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;

class OperadorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('operador')
            ->path('operador')
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
            ->brandLogo(asset('images/logo222.svg'))
            ->favicon(asset('images/logocheques.svg'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Operador/Pages'), for: 'App\\Filament\\Operador\\Pages')
            ->pages([
            ])
            ->discoverWidgets(in: app_path('Filament/Operador/Widgets'), for: 'App\\Filament\\Operador\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->resources([
                CasosListadoResource::class,
                LlamadasResource::class,
                CasoResource::class,

            ])
            ->pages([
                Dashboard::class,
            ])
            ->plugins([
                \DiscoveryDesign\FilamentGaze\FilamentGazePlugin::make(),
                FilamentProgressbarPlugin::make()->color('#206bc4'),
                StickyHeaderPlugin::make()->floating()
                    ->colored(),
                FilamentBackgroundsPlugin::make()->imageProvider(
                    MyImages::make()
                        ->directory('images/backgrounds')
                ),
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

<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ResumenDia;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\Panel\Concerns\HasRenderHooks;
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
use Livewire\Features\SupportTesting\Render;
use Tapp\FilamentAuthenticationLog\FilamentAuthenticationLogPlugin;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Awcodes\FilamentStickyHeader\StickyHeaderPlugin;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;

class AdminPanelProvider extends PanelProvider
{
    use HasRenderHooks;
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->sidebarWidth('15rem')
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            //    ->font('Josefin Sans') //A
            ->darkMode(false)
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
            /*  ->navigationGroups([
                  NavigationGroup::make('Casos')
                      ->label('Casos')
                      ->collapsed()
                      ->icon('heroicon-o-shopping-cart'),

              ])*/
            ->brandLogo(asset('images/logo222.svg'))
            ->favicon(asset('images/logocheques.svg'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->plugins([
                \DiscoveryDesign\FilamentGaze\FilamentGazePlugin::make(),
                FilamentAuthenticationLogPlugin::make(),
                FilamentProgressbarPlugin::make()->color('#206bc4'),
                FilamentSpatieRolesPermissionsPlugin::make(),
                StickyHeaderPlugin::make()->floating()
                    ->colored(),
                 FilamentBackgroundsPlugin::make()->imageProvider(
                       MyImages::make()
                           ->directory('images/backgrounds')
                   ),
            ])
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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

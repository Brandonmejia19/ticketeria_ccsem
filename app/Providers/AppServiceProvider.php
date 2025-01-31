<?php

namespace App\Providers;

use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use App\Http\Livewire\ResourceCreateModal;
use DiogoGPinto\AuthUIEnhancer\Concerns\BackgroundAppearance;
use DiogoGPinto\AuthUIEnhancer\Concerns\FormPanelWidth;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use FormPanelWidth;

    use BackgroundAppearance;
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}

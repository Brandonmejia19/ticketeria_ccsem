<?php

namespace TomatoPHP\FilamentNotes;

use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentNotes\Livewire\NoteAction;


class FilamentNotesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentNotes\Console\FilamentNotesInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-notes.php', 'filament-notes');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-notes.php' => config_path('filament-notes.php'),
        ], 'filament-notes-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-notes-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-notes');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-notes'),
        ], 'filament-notes-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-notes');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-notes'),
        ], 'filament-notes-lang');

        Livewire::component('note-action', NoteAction::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function boot(): void
    {
        //
    }
}

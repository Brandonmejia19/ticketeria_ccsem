<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CasosDia;
use Filament\Pages\Page;
class ResumenDia extends Dashboard
{
    protected static string $routePath = 'resumen';
    protected static ?string $title = 'Resumen del Día';
    protected static ?string $navigationGroup = 'Estadísticas';
    protected static ?string $navigationLabel = 'Dashboard por Día';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    public function getWidgets(): array
    {
        return [
            CasosDia::class
        ];
    }
    // public static string $view = 'filament.pages.resumen-dia';

}

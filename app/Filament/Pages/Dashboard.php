<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BotonesEspera;
use App\Filament\Widgets\CallInfoWidget;
use App\Filament\Widgets\CasosPropios;
use App\Filament\Widgets\CasosRecientesWidget;
use App\Filament\Widgets\ResumenDias;
use App\Filament\Widgets\TiposCasos;
use App\Filament\Widgets\TopBarWidget;
use App\Filament\Widgets\AdvancedStatsOverviewWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string $routePath = '';
    protected static ?string $title = 'Vista de Espera';
    protected static ?int $navigationSort = 15;
    public function getWidgets(): array
    {
        return [
            TopBarWidget::class,
            ResumenDias::class,
            CasosPropios::class,
            TiposCasos::class,
        ];
    }

    public function getColumns(): array|int|string
    {
        return 6;
    }
}

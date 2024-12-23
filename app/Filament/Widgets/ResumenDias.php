<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Caso;
use App\Models\Llamadas;

class ResumenDias extends BaseWidget
{
    protected static ?int $sort = -2;
    protected static bool $isLazy = false;
    protected ?string $heading = 'Analytics';
    protected ?string $description = 'An overview of some analytics.';
    protected function getStats(): array
    {
        $casosDelDia = Caso::query()
            ->where('usuario', auth()->user()->name)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $llamadasDelDia = Llamadas::query()
            ->where('usuario', auth()->user()->name)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return [
            Stat::make('Casos creados en el Día', $casosDelDia)
                ->description('Casos creados el día ' . now()->format('d-m-Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Llamadas tomadas del Día', $llamadasDelDia)
                ->description('Llamadas tomadas el día ' . now()->format('d-m-Y'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
           
        ];
    }
    public function getColumnSpan(): array|int|string
    {
        return 'full';
    }

}

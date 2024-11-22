<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Caso;

class CasosDia extends BaseWidget
{
    protected static string $routePath = 'resumen';

    protected static ?string $navigationGroup = 'Estadísticas';
    protected static ?int $sort = 2;
    protected static ?string $label = ' CASO: ATENCIÓN PH';
    protected static bool $isLazy = true;
    protected function getStats(): array
    {
        Carbon::setLocale('es');
        $casosdia = $this->filters['casos'] ?? null;

        $casosdia = Caso::query()
            ->when($casosdia, fn($query) => $query->where('created_at', Carbon::now()))
            ->count();

        return [
            Stat::make('Casos Creados en el Día', $casosdia)
                ->description('32k increase')
                ->chart([15,5,8,7,85,45])->color('primary')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Bounce rate', '21%')
                ->description('7% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Average time on page', '3:12')
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}

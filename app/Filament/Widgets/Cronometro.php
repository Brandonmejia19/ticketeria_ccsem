<?php

namespace App\Filament\Widgets;

use Dotenv\Parser\Value;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Cronometro extends BaseWidget
{
    /*
    protected static bool $isLazy = false;
    protected static ?string $pollingInterval = '1s';
    protected function getStats(): array
    {

        // Iniciar la sesión para almacenar el tiempo de inicio
        if (!session()->has('cronometro_start')) {
            session(['cronometro_start' => now()]);
        }

        // Calcular el tiempo transcurrido
        $start = session('cronometro_start');
        $now = now();
        $diffInSeconds = $now->diffInSeconds($start);

        // Convertir los segundos a formato HH:MM:SS
        $hours = str_pad(floor($diffInSeconds / 3600), 2, '0', STR_PAD_LEFT);
        $minutes = str_pad(floor(($diffInSeconds % 3600) / 60), 2, '0', STR_PAD_LEFT);
        $seconds = str_pad($diffInSeconds % 60, 2, '0', STR_PAD_LEFT);

        $formattedTime = "{$hours}:{$minutes}:{$seconds}";

        return [
            Stat::make('Cronómetro', $formattedTime)
                ->extraAttributes(['class' => 'text-2xl font-bold'])->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Extension', '7001')
                ->extraAttributes(['class' => 'text-2xl font-bold'])->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }*/
}

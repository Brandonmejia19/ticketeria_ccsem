<?php

namespace App\Filament\Widgets;

use App\Models\Caso;
use App\Models\Llamadas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TiposCasos extends ChartWidget
{
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Llamadas por Tipo';

    protected function getData(): array
    {
        // Obtener las llamadas del día agrupadas por tipo de caso
        $llamadasDelDia = LLamadas::query()
            ->select('tipo_caso', DB::raw('COUNT(*) as count'))
            ->where('usuario', auth()->user()->name)
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('tipo_caso')
            ->get();

        // Construir los datos para el gráfico
        $labels = $llamadasDelDia->pluck('tipo_caso')->toArray(); // Tipos de caso
        $data = $llamadasDelDia->pluck('count')->toArray(); // Cantidad de llamadas por tipo de caso

        return [
            'datasets' => [
                [
                    'label' => 'Llamadas por Tipo de Caso',
                    'data' => $data,
                    'backgroundColor' => ['#36A2EB','#e35a32','#f1cf70','#a80e50'],
                    'borderColor' => '#fff',
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'pie';
    }
    public function getColumnSpan(): array|int|string
    {
        return 2;
    }
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'display' => false,
                    ],
                ],
            ],
            'tooltip' => [
                'enabled' => true,
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'datalabels' => [
                    'display' => true,
                    'align' => 'bottom',
                    'backgroundColor' => '#ccc',
                    'borderRadius' => 3,
                    'font' => [
                        'size' => 18,
                    ]
                ],


            ],
        ];
    }
}

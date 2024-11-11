<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CallInfoWidget extends Widget
{
    public function getColumnSpan(): array|int|string
    {
        return 1;
    }
    protected static ?int $sort = -2;
    protected static string $view = 'filament.widgets.call-info-widget';
    protected static ?string $heading = 'Información de la llamada';

    protected function getViewData(): array
    {
        return [
            'duration' => '00:00:00',
            'extension' => '7001',
        ];
    }
}

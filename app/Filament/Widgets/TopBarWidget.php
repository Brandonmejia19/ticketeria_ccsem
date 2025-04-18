<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class TopBarWidget extends Widget
{
    public function getColumnSpan(): array|int|string
    {
        return 'full';
    }
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;
     protected static string $view = 'filament.widgets.top-bar-widget';


    protected function getViewData(): array
    {
        return [
            'username' => auth()->user()->name ?? 'usuario.operador',
            'extension' => '7001',  // Aquí puedes cambiar o traer el número de extensión dinámicamente si es necesario.
        ];
    }
}

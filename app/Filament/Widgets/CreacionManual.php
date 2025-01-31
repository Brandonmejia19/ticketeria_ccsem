<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LlamadasResource;
use App\Filament\Resources\LlamadasResource\Pages\ListLlamadas;
use App\Models\Llamadas;
use Filament\Widgets\Widget;
use Filament\Actions;
use Filament\Support\Enums\Alignment;
use App\Models\Caso;
use App\Filament\Resources\LlamadasResource\Pages;
use Livewire\Component;

class CreacionManual extends Widget
{
    protected static bool $isLazy = false;
    protected static string $view = 'filament.widgets.creacion-manual';
    public function getColumnSpan(): array|int|string
    {
        return 1;
    }
    public function getResources(): array
    {
        // Incluye los resources que deseas mostrar en el dashboard
        return [
      LlamadasResource::class,

        ];
    }
}

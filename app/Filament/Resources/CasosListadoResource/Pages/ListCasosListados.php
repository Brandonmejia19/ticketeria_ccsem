<?php

namespace App\Filament\Resources\CasosListadoResource\Pages;

use App\Filament\Resources\CasosListadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListCasosListados extends ListRecords
{
    protected static string $resource = CasosListadoResource::class;
    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'Atención PH' => Tab::make()->icon('healthicons-o-accident-and-emergency')->query(fn ($query) => $query->where('tipo_caso', 'Asistencia')),
            'Traslado' => Tab::make()->icon('healthicons-o-ambulance')->query(fn ($query) => $query->where('tipo_caso', 'Traslado')),
            'Informativa' => Tab::make()->icon('healthicons-o-crisis-response-center-person')->query(fn ($query) => $query->where('tipo_caso', 'Informativa')),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}

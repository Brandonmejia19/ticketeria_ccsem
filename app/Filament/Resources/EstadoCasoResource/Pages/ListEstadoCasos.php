<?php

namespace App\Filament\Resources\EstadoCasoResource\Pages;

use App\Filament\Resources\EstadoCasoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstadoCasos extends ListRecords
{
    protected static string $resource = EstadoCasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

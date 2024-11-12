<?php

namespace App\Filament\Resources\AmbulanciaResource\Pages;

use App\Filament\Resources\AmbulanciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAmbulancias extends ListRecords
{
    protected static string $resource = AmbulanciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

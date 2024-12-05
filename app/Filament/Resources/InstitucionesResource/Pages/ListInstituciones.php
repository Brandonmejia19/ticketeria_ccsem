<?php

namespace App\Filament\Resources\InstitucionesResource\Pages;

use App\Filament\Resources\InstitucionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstituciones extends ListRecords
{
    protected static string $resource = InstitucionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

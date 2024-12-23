<?php

namespace App\Filament\Resources\InfusionMedicamentosResource\Pages;

use App\Filament\Resources\InfusionMedicamentosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInfusionMedicamentos extends ListRecords
{
    protected static string $resource = InfusionMedicamentosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

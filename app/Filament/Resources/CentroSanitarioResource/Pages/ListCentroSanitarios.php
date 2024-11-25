<?php

namespace App\Filament\Resources\CentroSanitarioResource\Pages;

use App\Filament\Resources\CentroSanitarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentroSanitarios extends ListRecords
{
    protected static string $resource = CentroSanitarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

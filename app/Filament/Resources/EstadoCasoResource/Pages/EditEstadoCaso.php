<?php

namespace App\Filament\Resources\EstadoCasoResource\Pages;

use App\Filament\Resources\EstadoCasoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstadoCaso extends EditRecord
{
    protected static string $resource = EstadoCasoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

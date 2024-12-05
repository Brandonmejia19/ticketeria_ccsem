<?php

namespace App\Filament\Resources\InstitucionesResource\Pages;

use App\Filament\Resources\InstitucionesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstituciones extends EditRecord
{
    protected static string $resource = InstitucionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

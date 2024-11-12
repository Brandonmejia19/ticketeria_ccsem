<?php

namespace App\Filament\Resources\AmbulanciaResource\Pages;

use App\Filament\Resources\AmbulanciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAmbulancia extends EditRecord
{
    protected static string $resource = AmbulanciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

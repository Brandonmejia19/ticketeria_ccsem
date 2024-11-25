<?php

namespace App\Filament\Resources\CentroSanitarioResource\Pages;

use App\Filament\Resources\CentroSanitarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentroSanitario extends EditRecord
{
    protected static string $resource = CentroSanitarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

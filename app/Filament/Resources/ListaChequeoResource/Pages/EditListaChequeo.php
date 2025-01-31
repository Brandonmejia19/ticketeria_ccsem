<?php

namespace App\Filament\Resources\ListaChequeoResource\Pages;

use App\Filament\Resources\ListaChequeoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListaChequeo extends EditRecord
{
    protected static string $resource = ListaChequeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

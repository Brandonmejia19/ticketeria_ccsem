<?php

namespace App\Filament\Resources\TrasladoNoefectivoResource\Pages;

use App\Filament\Resources\TrasladoNoefectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrasladoNoefectivo extends EditRecord
{
    protected static string $resource = TrasladoNoefectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

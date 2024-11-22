<?php

namespace App\Filament\Resources\LlamadasResource\Pages;

use App\Filament\Resources\LlamadasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLlamadas extends EditRecord
{
    protected static string $resource = LlamadasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

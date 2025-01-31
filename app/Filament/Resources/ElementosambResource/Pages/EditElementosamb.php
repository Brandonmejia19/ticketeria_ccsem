<?php

namespace App\Filament\Resources\ElementosambResource\Pages;

use App\Filament\Resources\ElementosambResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditElementosamb extends EditRecord
{
    protected static string $resource = ElementosambResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

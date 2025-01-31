<?php

namespace App\Filament\Resources\BosemResource\Pages;

use App\Filament\Resources\BosemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBosem extends EditRecord
{
    protected static string $resource = BosemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

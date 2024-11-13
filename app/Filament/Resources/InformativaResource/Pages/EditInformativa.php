<?php

namespace App\Filament\Resources\InformativaResource\Pages;

use App\Filament\Resources\InformativaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformativa extends EditRecord
{
    protected static string $resource = InformativaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\InformativaResource\Pages;

use App\Filament\Resources\InformativaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformativas extends ListRecords
{
    protected static string $resource = InformativaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

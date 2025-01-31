<?php

namespace App\Filament\Resources\ElementosambResource\Pages;

use App\Filament\Resources\ElementosambResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListElementosambs extends ListRecords
{
    protected static string $resource = ElementosambResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

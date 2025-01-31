<?php

namespace App\Filament\Resources\HerramientasambResource\Pages;

use App\Filament\Resources\HerramientasambResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHerramientasambs extends ListRecords
{
    protected static string $resource = HerramientasambResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

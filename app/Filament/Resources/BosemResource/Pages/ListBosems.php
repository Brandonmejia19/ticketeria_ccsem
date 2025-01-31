<?php

namespace App\Filament\Resources\BosemResource\Pages;

use App\Filament\Resources\BosemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBosems extends ListRecords
{
    protected static string $resource = BosemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

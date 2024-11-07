<?php

namespace App\Filament\Resources\AuditsResource\Pages;

use App\Filament\Resources\AuditsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAudits extends ListRecords
{
    protected static string $resource = AuditsResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}

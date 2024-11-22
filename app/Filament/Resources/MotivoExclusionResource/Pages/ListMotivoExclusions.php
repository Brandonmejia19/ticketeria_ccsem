<?php

namespace App\Filament\Resources\MotivoExclusionResource\Pages;

use App\Filament\Resources\MotivoExclusionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMotivoExclusions extends ListRecords
{
    protected static string $resource = MotivoExclusionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

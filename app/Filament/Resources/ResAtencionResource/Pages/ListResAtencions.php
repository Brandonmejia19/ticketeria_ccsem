<?php

namespace App\Filament\Resources\ResAtencionResource\Pages;

use App\Filament\Resources\ResAtencionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResAtencions extends ListRecords
{
    protected static string $resource = ResAtencionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

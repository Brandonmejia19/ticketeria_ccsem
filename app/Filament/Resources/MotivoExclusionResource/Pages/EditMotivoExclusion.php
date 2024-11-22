<?php

namespace App\Filament\Resources\MotivoExclusionResource\Pages;

use App\Filament\Resources\MotivoExclusionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMotivoExclusion extends EditRecord
{
    protected static string $resource = MotivoExclusionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

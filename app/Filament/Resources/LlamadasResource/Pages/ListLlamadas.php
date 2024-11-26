<?php

namespace App\Filament\Resources\LlamadasResource\Pages;

use App\Filament\Pages\AsociarCaso;
use App\Filament\Resources\CasoResource;
use App\Filament\Resources\CasosListadoResource\Pages\ListCasosListados;
use App\Filament\Resources\LlamadasResource;
use App\Models\Llamadas;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Caso;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Select;

class ListLlamadas extends ListRecords
{
    protected static string $resource = LlamadasResource::class;
    protected bool $editing = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Guardar Llamada')->label('Ingreso de llamada')
                ->modelLabel(
                    'Llamada'
                )->modalSubmitActionLabel('Cerrar Llamada')
                ->icon('healthicons-f-call-centre')
                ->color('primary')
                ->modalHeading('Llamada Entrante')
                ->modalDescription('Captura de Datos de llamada')
                ->modalIcon('healthicons-f-call-centre')->modalAlignment('center')
                ->createAnother(false)
                ->modalCancelAction(false)


        ];
    }
}

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
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Notification;

class ListLlamadas extends ListRecords
{

    protected static string $resource = LlamadasResource::class;
    protected bool $editing = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Guardar Llamada')
            ->modalAlignment(Alignment::Center)
         //   ->modalIcon('heroicon-o-truck')
                ->label('Ingreso de llamada')
                ->modelLabel('Llamada')
                ->modalSubmitActionLabel('Cerrar Llamada')
                ->color('primary')
                ->modalHeading('Ingreso de Registro de Llamada')
                ->successNotificationMessage('Llamada Creada')
                ->createAnother(false)
                //->slideOver()
                ->modalCancelAction(false)
                ->successRedirectUrl(function ($record) {
                    if ($record->tipo_caso === 'Asistencia') {
                        // Crear el caso automáticamente
                        $caso = Caso::create([
                            'llamada_id' => $record->id, // Asignar el ID de la llamada
                            'usuario' => auth()->user()->name,
                            'llamada_asociada' => $record->llamada_correlativo,
                            'tipo_caso' => $record->tipo_caso,
                        ]);
                        return route('filament.operador.resources.casos.edit', ['record' => $caso->id]);
                    }
                    return route('filament.operador.resources.llamadas.index');
                })

        ];
    }
}

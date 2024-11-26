<?php

namespace App\Filament\Resources\LlamadasResource\Pages;

use App\Filament\Pages\AsociarCaso;
use App\Filament\Resources\CasoResource;
use App\Filament\Resources\CasosListadoResource\Pages\ListCasosListados;
use App\Filament\Resources\LlamadasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Caso;

class ListLlamadas extends ListRecords
{
    protected static string $resource = LlamadasResource::class;
    protected bool $editing = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('Guardar Llamada')->label('Ingreso de llamada')->createAnother(false)->modelLabel('Llamada')->modalSubmitActionLabel('Cerrar Llamada')->modalActions([
                Actions\Action::make('crear_atencion')
                    ->label('Crear Atención')
                    ->color('warning')
                    ->icon('heroicon-o-plus')
                    ->action(fn() => $this->crearAtencion()),
                Actions\Action::make('cerrar_llamada')
                    ->label('Cerrar Llamada')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->action(fn() => $this->cerrarLlamada()),
                Actions\Action::make('asociar_llamada')
                    ->label('Asociar Llamada')
                    ->icon('heroicon-o-link')
                    ->modalHeading('Seleccionar Caso para Asociar')
                    ->color('success')
                    ->modalContent([])
                    ->action(fn() => $this->asociarLlamada()),
            ])
        ];
    }
}

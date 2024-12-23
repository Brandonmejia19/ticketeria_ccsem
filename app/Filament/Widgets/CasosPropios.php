<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Caso;
use Illuminate\Support\Facades\Auth;

class CasosPropios extends BaseWidget
{
    protected static ?string $heading = 'Casos Propios - Ultimas 12 Horas';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Caso::query()
                    ->where('usuario', auth()->user()->name) // Filtra por el usuario autenticado
                    ->where('created_at', '>=', now()->subDay()) // Filtra registros de las últimas 24 horas
                    ->orderBy('created_at', 'desc') // Ordena por fecha de creación descendente
            )
            ->columns([
                'tipo_caso' => Tables\Columns\TextColumn::make('tipo_caso')->placeholder('Sin Datos')->searchable()->label('Tipo de Caso')->icon('heroicon-o-ticket')->sortable()->badge()->color(function ($record) {
                    $tipo_caso = $record->tipo_caso;
                    if ($tipo_caso === 'Asistencia') {
                        return 'red'; // Rojo
                    }
                    if ($tipo_caso === 'Traslado') {
                        return 'verde'; // Cambia "amarillo" a "yellow"
                    }
                    if ($tipo_caso === 'Informativa') {
                        return 'primary'; // Verde
                    }

                    return null; // Devuelve null si no hay coincidencia
                })->alignCenter()
                    ->wrap(),
                'correlativo_caso' => Tables\Columns\TextColumn::make('correlativo_caso')->searchable()->label('Correlativo')->sortable()->alignCenter()->badge()->placeholder('Sin Datos'),
                'nombres_paciente' => Tables\Columns\TextColumn::make('nombres_paciente')->searchable()->label('Paciente N.')->sortable()->alignCenter()->icon('heroicon-s-user-circle')->placeholder('Sin Datos'),
                'apellidos_paciente' => Tables\Columns\TextColumn::make('apellidos_paciente')->searchable()->label('Paciente A.')->sortable()->alignCenter()->icon('heroicon-s-user-circle')->placeholder('Sin Datos'),
                //  'prioridad' => Tables\Columns\TextColumn::make('prioridad')->label('Prioridad'),
                'color' => Tables\Columns\ColorColumn::make('color')->alignCenter()->label('Prioridad')->placeholder('Sin Datos')
                    ->default(function ($record) {
                        $prioridad = $record->prioridad;
                        if ($prioridad === '1') {
                            return '#ff0303'; // Rojo
                        }
                        if ($prioridad === '2') {
                            return 'yellow'; // Cambia "amarillo" a "yellow"
                        }
                        if ($prioridad === '3') {
                            return '#03ff1c'; // Verde
                        }
                        if ($prioridad === '4') {
                            return '#03c0ff'; // Azul
                        }
                        return null;
                    })
                    ->wrap(),
                //   'user_id.name' => Tables\Columns\ColorColumn::make('user_id')->label('Operador'),

                'created_at' => Tables\Columns\TextColumn::make('created_at')->searchable()->alignCenter()->label('Fecha de Creación')->placeholder('Sin Datos'),
            ]);
        /*  ->actions([
              Tables\Actions\ActionGroup::make([
              //    Tables\Actions\ViewAction::make()->color('primary'),
              ])
          ]);*/
    }
    public function getColumnSpan(): array|int|string
    {
        return '4';
    }
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

}

<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Caso;
use Filament\Tables\Column;

class CasosRecientesWidget extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = -1;
    public function getColumnSpan(): array|int|string
    {
        return 2;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Caso::query()
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                // Define aquí las columnas que quieres mostrar en la tabla
                'tipo_caso' => Tables\Columns\TextColumn::make('tipo_caso')->label('Tipo de Caso')->icon('heroicon-o-ticket')->sortable()->badge()->color(function ($record) {
                    $tipo_caso = $record->tipo_caso;
                    if ($tipo_caso === 'Atención PH') {
                        return 'red'; // Rojo
                    }
                    if ($tipo_caso === 'Traslado') {
                        return 'verde'; // Cambia "amarillo" a "yellow"
                    }
                    if ($tipo_caso === 'Informativa') {
                        return 'primary'; // Verde
                    }

                    return null; // Devuelve null si no hay coincidencia
                })
                    ->wrap(),
                'nu_caso' =>  Tables\Columns\TextColumn::make('nu_caso')->label('Número de Caso')->sortable()->prefix('#')->badge()->color('verde'),
                'nombre_alertante' => Tables\Columns\TextColumn::make('nombre_alertante')->label('Nombre del Alertante')->sortable()->icon('heroicon-s-user'),
                //  'prioridad' => Tables\Columns\TextColumn::make('prioridad')->label('Prioridad'),
                'color' => Tables\Columns\ColorColumn::make('color')->label('Prioridad')
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

                'created_at' =>  Tables\Columns\TextColumn::make('created_at')->label('Fecha de Creación'),
                // Añade más columnas según los datos que quieras mostrar
            ]);
    }
}

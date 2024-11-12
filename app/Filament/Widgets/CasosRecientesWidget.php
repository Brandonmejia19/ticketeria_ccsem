<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Caso;
use Filament\Tables\Column;
class CasosRecientesWidget extends BaseWidget
{
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
            'tipo_caso' => Tables\Columns\TextColumn::make('tipo_caso')->label('Tipo de Caso'),
            'nu_caso' =>  Tables\Columns\TextColumn::make('nu_caso')->label('Número de Caso'),
            'nombre_alertante' => Tables\Columns\TextColumn::make('nombre_alertante')->label('Nombre del Alertante'),
            'created_at' =>  Tables\Columns\TextColumn::make('created_at')->label('Fecha de Creación'),
            // Añade más columnas según los datos que quieras mostrar
        ]);
    }
}

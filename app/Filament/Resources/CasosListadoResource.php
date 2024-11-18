<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CasosListadoResource\Pages;
use App\Filament\Resources\CasosListadoResource\RelationManagers;
use App\Models\Caso;
use Doctrine\DBAL\Query;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;

class CasosListadoResource extends Resource
{
    protected static ?string $label = 'Listado de Casos';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Casos Listado';
    protected static ?string $model = Caso::class;
    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nu_caso')
                    ->icon('heroicon-o-ticket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_caso')->label('Tipo de Caso')->icon('heroicon-o-ticket')->sortable()->badge()->color(function ($record) {
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
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('te_alertante')
                    ->icon('heroicon-o-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_alertante')
                    ->icon('heroicon-o-user-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombres_paciente')
                    ->icon('heroicon-o-user-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellidos_paciente')
                    ->icon('heroicon-o-user')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')->label('Prioridad')
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->icon('heroicon-o-calendar')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ultima Actualización')
                    ->icon('heroicon-o-calendar')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])

            //////////////////////////////////////////////////////////////////////////////////////////////////////

            ->filters([
                Filter::make('No. Ticket')
                    ->form([
                        Forms\Components\TextInput::make('nu_caso')
                            ->label('No. Ticket')
                            ->placeholder('Ingrese número de ticket')->prefixIcon('heroicon-o-ticket'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            isset($data['nu_caso']) && $data['nu_caso'], // Verifica que la clave exista y no esté vacía
                            fn(Builder $query, $nu_caso): Builder => $query->where('nu_caso', '===', "%{$nu_caso}%")
                        );
                    }),

                Filter::make('Teléfono Alertante')
                    ->form([
                        Forms\Components\TextInput::make('telefono_alertante')
                            ->label('Teléfono Alertante')
                            ->placeholder('Ingrese teléfono del alertante')->prefixIcon('heroicon-o-phone'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['telefono_alertante'],
                            fn(Builder $query, $telefono): Builder => $query->where('telefono_alertante', 'like', "%{$telefono}%")
                        );
                    }),

                SelectFilter::make('tipo_caso')
                    ->label('Tipo de Atención')
                    ->searchable()
                    ->preload()
                    ->options([
                        'Atención PH' => 'Atención PH',
                        'Traslado' => 'Traslado',
                        'Informativa' => 'Informativa',
                    ]),
                Filter::make('Nombre Alertante')
                    ->form([
                        Forms\Components\TextInput::make('nombre_alertante')
                            ->label('Nombre Alertante')->prefixIcon('healthicons-o-ui-folder-family')
                            ->placeholder('Ingrese nombre del alertante'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nombre_alertante'],
                            fn(Builder $query, $nombre): Builder => $query->where('nombre_alertante', '===', $nombre)
                        );
                    }),

                Filter::make('Nombre Paciente')
                    ->form([
                        Forms\Components\TextInput::make('nombre_paciente')->prefixIcon('heroicon-o-user')
                            ->label('Nombre Paciente')
                            ->placeholder('Ingrese nombre del paciente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nombre_paciente'],
                            fn(Builder $query, $nombre): Builder => $query->where('nombre_paciente', 'like', "%{$nombre}%")
                        );
                    })->columnSpan(2),
                Filter::make('Apellidos Paciente')
                    ->form([
                        Forms\Components\TextInput::make('apellidos_paciente')
                            ->label('Apellidos Paciente')
                            ->prefixIcon('heroicon-o-user')
                            ->placeholder('Ingrese apellidos del paciente'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['apellidos_paciente'],
                            fn(Builder $query, $nombre): Builder => $query->where('apellidos_paciente', 'like', "%{$nombre}%")
                        );
                    })->columnSpan(2),
                SelectFilter::make('codigo_ambulancia')
                    ->label('Cod. Ambulancia')
                    ->searchable()
                    ->preload()
                    ->options([
                        // Opciones de código de ambulancia (ejemplo)
                        'A001' => 'Ambulancia A001',
                        'A002' => 'Ambulancia A002',
                        // Añade más opciones aquí
                    ]),
                Filter::make('Diagnóstico Presuntivo')
                    ->form([
                        Forms\Components\TextInput::make('diagnostico_presuntivo')->prefixIcon('heroicon-o-clipboard-document-list')
                            ->label('Diagnóstico Presuntivo')
                            ->placeholder('Ingrese diagnóstico presuntivo'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['diagnostico_presuntivo'],
                            fn(Builder $query, $diagnostico): Builder => $query->where('diagnostico_presuntivo', 'like', "%{$diagnostico}%")
                        );
                    }),

                Filter::make('Notas')
                    ->form([
                        Forms\Components\TextInput::make('notas')
                            ->label('Notas')->prefixIcon('heroicon-o-chat-bubble-bottom-center-text')
                            ->placeholder('Ingrese notas'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['notas'],
                            fn(Builder $query, $notas): Builder => $query->where('notas', 'like', "%{$notas}%")
                        );
                    }),

                SelectFilter::make('codigo_actuacion')
                    ->label('Código de Actuación de Ambulancia')
                    ->searchable()
                    ->preload()
                    ->options([
                        // Opciones de código de actuación de ambulancia (ejemplo)
                        'C001' => 'Actuación C001',
                        'C002' => 'Actuación C002',
                        // Añade más opciones aquí
                    ]),

                SelectFilter::make('prioridad')
                    ->label('Prioridad')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ]),
                Filter::make('Fecha Ticket')
                    ->form([
                        DatePicker::make('fecha_inicial')->label('Fecha Ticket Desde')->columnSpan(4)->prefixIcon('heroicon-o-calendar'),
                        DatePicker::make('fecha_final')->label('Fecha Ticket Hasta')->columnSpan(4)->prefixIcon('heroicon-o-calendar'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_inicial'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['fecha_final'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    })->columnSpan(4)->columns(8),
            ], layout: FiltersLayout::AboveContentCollapsible)

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCasosListados::route('/'),
            'create' => Pages\CreateCasosListado::route('/create'),
            'edit' => Pages\EditCasosListado::route('/{record}/edit'),
        ];
    }
}

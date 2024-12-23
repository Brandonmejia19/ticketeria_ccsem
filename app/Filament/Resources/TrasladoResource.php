<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrasladoResource\Pages;
use App\Filament\Resources\TrasladoResource\RelationManagers;
use App\Models\Caso;
use App\Models\infusion_medicamentos;
use App\Models\TipoCaso;
use App\Models\Traslado;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Carbon\Carbon;
use App\Models\Llamadas;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Columns\TextColumn;

class TrasladoResource extends Resource
{
    protected static ?int $navigationSort = 4;

    protected static ?string $model = Caso::class;
    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: TRASLADO';
    protected static ?string $navigationLabel = 'Traslado';
    protected static ?string $navigationIcon = 'healthicons-o-ambulance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Traslado')
                    ->schema([
                        Fieldset::make('Información de Llamada')
                            ->schema([
                                Forms\Components\TextInput::make('usuario')
                                    ->label('Usuario')
                                    ->reactive()
                                    ->prefixIcon('heroicon-o-user')
                                    ->readOnly()
                                    ->columnSpan(2)
                                    ->default(fn() => Auth::user()->name),
                                Forms\Components\Select::make('llamada_id')
                                    ->options(Llamadas::limit(5)->pluck('llamada_correlativo', 'id')) // Obtiene las opciones.
                                    ->searchable()
                                    ->prefixIcon('heroicon-o-phone-arrow-down-left')
                                    ->placeholder('Selecciona una llamada')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            // Buscar la llamada en la base de datos.
                                            $llamada = Llamadas::find($state);

                                            if ($llamada) {
                                                // Si la llamada existe, asignar su correlativo al campo.
                                                $set('llamada_asociada', $llamada->llamada_correlativo);
                                                $set('hora_creacion', $llamada->hora_creacion);
                                                $set('telefono_alertante', $llamada->telefono_alertante);
                                                $set('nombre_alertante', $llamada->nombre_alertante);
                                                $set('motivo_literal', $llamada->motivo_literal);
                                                $set('tipo_caso2', $llamada->tipo_caso);
                                                $set('descripcion_caso', $llamada->descripcion_caso);
                                            } else {
                                                // Si no se encuentra, limpiar el campo.
                                                $set('llamada_asociada', null);
                                            }
                                        } else {
                                            +
                                                // Si no hay estado, limpiar el campo.
                                                $set('llamada_asociada', null);
                                        }
                                    })
                                    ->label('Llamada Correlativo'),
                                Forms\Components\TextInput::make('llamada_asociada')
                                    ->prefixIcon('heroicon-o-phone-arrow-down-left')
                                    ->placeholder('Llamada Asociada')
                                    ->readOnly() // Este campo no se edita directamente.
                                    ->default('Sin datos') // Valor inicial predeterminado.
                                    ->label('Llamada Asociada'),
                                Forms\Components\TextInput::make('hora_creacion')
                                    ->disabled()
                                    ->prefixIcon('healthicons-o-i-schedule-school-date-time')
                                    ->reactive()
                                    ->placeholder('Datos de Llamada')
                                    ->label('Hora y Origen de creación'),
                                Forms\Components\TextInput::make('telefono_alertante')
                                    ->disabled()
                                    ->prefixIcon('heroicon-o-phone-x-mark')
                                    ->placeholder('Datos de Llamada')
                                    ->reactive()
                                    ->prefixIcon('healthicons-o-i-schedule-school-date-time')
                                    ->label('Telefono Alertante'),
                                Forms\Components\TextInput::make('nombre_alertante')
                                    ->placeholder('Datos de Llamada')
                                    ->disabled()
                                    ->reactive()
                                    ->prefixIcon('heroicon-o-user-circle')
                                    ->label('Nombre Alertante'),
                                Forms\Components\TextInput::make('motivo_literal')
                                    ->columnSpan(2)
                                    ->prefixIcon('heroicon-o-pencil-square')
                                    ->disabled()
                                    ->placeholder('Datos de Llamada')
                                    ->reactive()
                                    ->label('Motivo Literal de la Llamada'),
                                Forms\Components\TextInput::make('tipo_caso2')
                                    ->prefixIcon('heroicon-o-archive-box')
                                    ->placeholder('Datos de Llamada')
                                    ->reactive()
                                    ->disabled()
                                    ->label('Tipo de Caso'),
                                Forms\Components\TextInput::make('descripcion_caso')
                                    ->placeholder('Datos de Llamada')
                                    ->disabled()
                                    ->prefixIcon('heroicon-o-clipboard-document-list')
                                    ->columnSpanFull()->reactive()
                                    ->label('Descripción de Caso'),
                                Forms\Components\TextInput::make('user_id')
                                    ->default(fn(): mixed => Auth::user()->id)
                                    ->hidden(),

                                Actions::make([
                                    Action::make('doctor')
                                        ->color('danger')
                                        ->icon('healthicons-o-doctor')
                                        ->label('Doctor'),
                                ])->alignRight()->columnSpanFull(),
                            ])->columns(3),
                        Fieldset::make('Datos de Paciente')->schema([
                            Forms\Components\TextInput::make('tipo_caso')
                                ->label('Tipo de Caso')
                                ->readOnly()
                                ->prefixIcon('heroicon-o-archive-box')
                                ->columnSpanFull()
                                ->default('Traslado'),
                            Forms\Components\TextInput::make('correlativo_caso')
                                ->hidden()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nombres_paciente')
                                ->columnSpan(2)
                                ->placeholder('Nombres de paciente')
                                ->prefixIcon('heroicon-o-user')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('apellidos_paciente')
                                ->prefixIcon('heroicon-o-user')
                                ->placeholder('Apellidos de paciente')
                                ->columnSpan(2)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('edad')
                                ->placeholder('00')
                                ->maxLength(3)
                                ->prefixIcon('heroicon-o-exclamation-circle')
                                ->columnSpan(2)
                                ->numeric(),
                            Forms\Components\Select::make('edad_complemento')
                                ->label('Complemento')
                                ->native()
                                ->options([
                                    'A' => 'A',
                                    'D' => 'D'
                                ])
                                ->default('A')
                                ->columnSpan(1),
                            Forms\Components\ToggleButtons::make('sexo')
                                ->columnSpan('1')
                                ->options([
                                    'M' => 'M',
                                    'F' => 'F'
                                ])
                                ->columns(2),
                            Forms\Components\Repeater::make('signos_vitales')
                                ->label('Datos de Signos Vitales')
                                ->schema([
                                    Forms\Components\TextInput::make('ta')
                                        ->label('TA')
                                        ->numeric()
                                        ->columnSpan(2) // Abarca 2 columnas en la grilla
                                        ->placeholder('000'),
                                    Forms\Components\TextInput::make('fc')
                                        ->label('FC')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('000'),
                                    Forms\Components\TextInput::make('pr')
                                        ->label('PR')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('000'),
                                    Forms\Components\TextInput::make('temp')
                                        ->label('Temp.')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('00.00'),
                                    Forms\Components\TextInput::make('sat_o2')
                                        ->label('Sat O2')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('00.00'),
                                    Forms\Components\TextInput::make('hgt')
                                        ->label('HGT')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('00.00'),
                                    Forms\Components\TextInput::make('sg')
                                        ->label('SG')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('00.00'),
                                    Forms\Components\TextInput::make('str')
                                        ->label('STR')
                                        ->numeric()
                                        ->columnSpan(2)
                                        ->placeholder('00.00'),
                                    Forms\Components\Select::make('estado')
                                        ->label('Estado E/I')
                                        ->options([
                                            'E' => 'E',
                                            'I' => 'I',
                                        ])
                                        ->columnSpan(2)
                                        ->placeholder('Seleccione Estado'),
                                    Forms\Components\DateTimePicker::make('fecha_hora')
                                        ->label('F. y Hora')
                                        ->default(now())
                                        ->columnSpan(3)
                                        ->format('Y-m-d H:i:s'),
                                ])
                                ->columnSpanFull()
                                ->columns(12)
                                ->addActionLabel('Agregar Fila')
                                ->reorderable(false)
                                ->deletable(false),
                            Forms\Components\Select::make('condicion_paciente')
                                ->prefixIcon('healthicons-o-sling')->options([
                                        'Estable' => 'Estable',
                                        'Embarazada crítico' => 'Embarazada crítico',
                                        'Neonato critico' => 'Neonato critico',
                                        'Niño crítico' => 'Niño crítico',
                                        'Adolescente critico' => 'Adolescente critico',
                                        'Adulto crítico' => 'Adulto crítico',
                                    ])
                                ->nullable()
                                ->columnSpan(2),
                            Forms\Components\Textarea::make('dirección')
                                ->placeholder('Ubicación del Paciente')
                                ->columnSpan(6),



                            Fieldset::make('Paciente Crítico')->schema([
                                Fieldset::make('Paciente Crítico')->schema([

                                    Forms\Components\ToggleButtons::make('ventilado')
                                        ->label('¿Ventilado?')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No',
                                        ])
                                        ->inline()
                                        ->columnSpan(1),
                                    Forms\Components\Select::make('modo_ventilacion')
                                        ->prefixIcon('healthicons-o-sling')->options([
                                                'VT' => 'VT',
                                                'PEEP' => 'PEEP',
                                                'PIP' => 'PIP',
                                                'Relación IE' => 'Relación IE',
                                                'FIO2  ' => 'FIO2',
                                            ])
                                        ->nullable()
                                        ->columnSpan(3),
                                    Forms\Components\ToggleButtons::make('bomba_infusion')
                                        ->label('¿Uso de bomba de infusión?')
                                        ->inline()
                                        ->icons(['healthicons-o-sling'])->options([
                                                'Si' => 'Si',
                                                'No' => 'No',
                                            ])

                                        ->nullable()
                                        ->columnSpan(2),
                                ])->columnSpan(1),
                                Fieldset::make('Datos de Médico')->schema([
                                    Repeater::make('bomba_infusion_medicamentos')->schema([
                                        Forms\Components\Select::make('medicamento')
                                            ->options(
                                                infusion_medicamentos::all()->pluck('name', 'name')
                                            )->columns(4)
                                            ->nullable()
                                            ->searchable()
                                            ->columnSpan(span: 2),
                                        Forms\Components\TextInput::make('cantidad')
                                            ->nullable()
                                            ->columnSpan(span: 1),
                                    ])->columnSpanFull()->columns(3)->reorderable(false)->deletable(false),
                                ])->columnSpan(1)
                            ])->columns(2)->columnSpanFull(),
                        ])->columns(8),
                        Fieldset::make('Datos de Médico')->schema([
                            Forms\Components\CheckboxList::make('modo_ventilacion')
                                ->options([])

                        ])->columns(6),
                        Fieldset::make('Información Médico')->schema([

                        ])->columns(6),
                        Fieldset::make('Gestión de Recursos')->schema([
                        ])->columns(6),
                    ])->columns(6)->columnSpanFull(),
            ])->columns(4);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('tipo_caso')->alignCenter()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('correlativo_caso')->alignCenter()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('llamada_asociada')
                    ->alignCenter()
                    ->badge()->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombres_paciente')->alignCenter()
                    ->label('No.Paciente')
                    ->searchable()->size(TextColumn\TextColumnSize::ExtraSmall),
                Tables\Columns\TextColumn::make('apellidos_paciente')->alignCenter()->label('Ap.Paciente')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->searchable(),
                Tables\Columns\TextColumn::make('tap')->alignCenter()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')->alignCenter()
                    ->inline()
                    ->label('Prioridad'),
                Tables\Columns\TextColumn::make('recurso_asignado')->alignCenter()
                    ->label('R.Asignado')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cie10')->alignCenter()
                    ->label('CIE10')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->searchable(),
                Tables\Columns\TextColumn::make('usuario')->alignCenter()
                    ->label('Operador')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->alignCenter()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->alignCenter()
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([ // Si deseas permitir filtros adicionales
                Tables\Filters\SelectFilter::make('tipo_caso')
                    ->label('Filtrar por Tipo de Caso')
                    ->hidden()
                    ->options([
                        'Traslado' => 'Traslado',
                        'Emergencia' => 'Emergencia',
                        'Consulta' => 'Consulta',
                    ])
                    ->default('Traslado'), // Opción predeterminada al cargar la página
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\AssociateAction::make()
                        ->recordSelectOptionsQuery(
                            fn(Builder $query) => $query->where('user_id', auth()->id()) // Filtra según el usuario autenticado
                        )
                        ->recordSelect(
                            fn(Forms\Components\Select $select) => $select
                                ->relationship('llamadas2', 'llamada_correlativo')
                                ->searchable()
                                ->preload()
                                ->multiple()
                                ->label('Llamadas Asociadas')
                                ->placeholder('Selecciona una o más llamadas')
                        )
                        ->icon('heroicon-o-link'),
                ])
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tipo_caso', '=', 'Traslado');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTraslados::route('/'),
            'create' => Pages\CreateTraslado::route('/create'),
            'edit' => Pages\EditTraslado::route('/{record}/edit'),
        ];
    }
}

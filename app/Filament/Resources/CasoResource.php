<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CasoResource\Pages;
use App\Filament\Resources\CasoResource\Pages\EditCaso;
use App\Filament\Resources\CasoResource\RelationManagers;
use App\Models\Caso;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use OpenSpout\Common\Entity\Cell\DateTimeCell;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\HtmlString;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Set;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Support\Enums\VerticalAlignment;

class CasoResource extends Resource
{
    protected static ?string $model = Caso::class;
    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: ATENCIÓN PH';
    protected static ?string $navigationLabel = 'Atención PH';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Atención PH')
                    ->schema([
                        Fieldset::make('INFORMACIÓN CASO')->schema([
                            Forms\Components\Select::make('tipo_caso')
                                ->prefixIcon('heroicon-o-folder-open')
                                ->label('Tipo de Caso')
                                ->options([
                                    'Atención PH' => 'Atención PH',
                                    'Traslado' =>  'Traslado',
                                    'Informativa' => 'Informativa',
                                ])
                                ->required()
                                ->columnSpan('2'),
                            Forms\Components\TextInput::make('nu_caso')
                                ->prefixIcon('heroicon-o-ticket')
                                ->label('N# Caso')
                                ->numeric()
                                ->columnSpan('2')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('0000000000'),
                            Forms\Components\TextInput::make('te_alertante')
                                ->placeholder('614668848')
                                ->label('Teléfono de Alertante')
                                ->prefixIcon('heroicon-o-phone')
                                ->columnSpan('2')
                                ->tel('8')
                                ->required()
                                ->maxLength(255),
                        ])->columns(6),
                        Fieldset::make('DATOS ALERTANTE')->schema([
                            Forms\Components\TextInput::make('motivo_literal')
                                ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text')
                                ->placeholder('Escriba el Motivo Literal de la llamada')
                                ->label('Motivo Literal')
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nombre_alertante')
                                ->prefixIcon('heroicon-o-user-circle')
                                ->placeholder('Nombre del Alertante')
                                ->label('Escriba el nombre de Alertante')
                                ->columnSpan(3)
                                ->required()
                                ->maxLength(255),
                        ])->columns(6),
                        Fieldset::make('DATOS PACIENTE')->schema([
                            Forms\Components\TextInput::make('nombres_paciente')
                                ->placeholder('Nombres del Paciente')
                                ->prefixIcon('heroicon-o-user')
                                ->label('Nombres del Paciente')
                                ->columnSpan('2')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('apellidos_paciente')
                                ->prefixIcon('heroicon-o-user')
                                ->label('Apellidos del Paciente')
                                ->placeholder('Escriba el apellido del Alertante')
                                ->required()
                                ->maxLength(255)->columnSpan('2'),
                            Forms\Components\TextInput::make('edad')
                                ->prefixIcon('heroicon-o-newspaper')
                                ->placeholder('00')
                                ->label('Edad')
                                ->columnSpan('1')
                                ->required()
                                ->numeric(),
                            Forms\Components\CheckboxList::make('sexo')
                                ->columnSpan('1')
                                ->options([
                                    'M' => 'M',
                                    'F' => 'F'
                                ])
                                ->columns(2)
                                ->required(),
                            Forms\Components\Select::make('departamento_id')
                                ->columnSpan('3')
                                ->prefixIcon('heroicon-o-globe-americas')
                                ->label('Departamentos')
                                ->required(),
                            Forms\Components\Select::make('distrito_id')
                                ->prefixIcon('heroicon-o-globe-americas')
                                ->columnSpan('3')
                                ->label('Distrito')
                                ->columnSpan('3')
                                ->required(),
                            Forms\Components\Textarea::make('dirección_operador')
                                ->placeholder('Dirección dada por alertante')
                                ->columnSpan('3')
                                ->label('Dirección')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('puntos_referencia')
                                ->placeholder('Puntos de referencia de dirección brindada')
                                ->columnSpan('3')
                                ->label('Puntos de Referencia')
                                ->required(),
                            Map::make('location')
                                ->label('Location')
                                ->columnSpanFull()
                                ->showMarker()
                                ->markerColor("red")
                                ->showFullscreenControl()
                                ->showZoomControl()
                                ->liveLocation(true)
                                ->zoom(15)
                                ->detectRetina()
                                ->draggable(),
                            Actions::make([
                                Action::make('Colocar pin')
                                    ->icon('heroicon-m-map-pin')
                                    ->action(function (Set $set, $livewire): void {
                                        // Asigna una ubicación predeterminada al mapa y a los campos de coordenadas
                                        $set('lat2', 'lat');
                                        $set('lng2', 'lng');
                                        $set('location', ['lat' => 'lat2', 'lng' => 'lng']);
                                        $set('latitude', 'lat');
                                        $set('longitude', 'lng');
                                        $livewire->dispatch('refreshMap');
                                    }),
                            ])->verticalAlignment(VerticalAlignment::Start),
                            Forms\Components\TextInput::make('latitude')
                                ->label('Latitude')
                                ->reactive(),
                            Forms\Components\TextInput::make('longitude')
                                ->label('Longitude')
                                ->reactive(),
                            Forms\Components\Textarea::make('notas_operador')
                                ->label('Notas')
                                ->placeholder('Notas dadas por el operador')
                                ->required()
                                ->columnSpanFull()
                        ])->columns(6),
                        Fieldset::make('Información Médico')->schema([
                            /////////////// //MEDICO
                            Forms\Components\Textarea::make('diagnostivo_presuntivo')
                                ->label('Diagnostico Presuntivo')
                                ->placeholder('Diagnostico Presuntivo')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),
                            Forms\Components\Select::make('ambulancia_id')
                                ->label('T. Ambulancia')
                                ->options([
                                    'A' => 'A',
                                    'B' => 'B',
                                    'C' => 'C',
                                ])
                                ->prefixIcon('heroicon-o-truck')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\Select::make('prioridad')
                                ->label('Prioridad')
                                ->prefixIcon('heroicon-o-exclamation-triangle')
                                ->options([
                                    '1' => '1',
                                    '2' => '2',
                                    '3' => '3',
                                    '4' => '4',
                                ])
                                ->placeholder('Opción')
                                ->required()
                                ->columnSpan(1)
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    // Cambia el color del ColorPicker basado en la prioridad seleccionada
                                    $color = match ($state) {
                                        '1' => '#FF0000', // Rojo para prioridad 1
                                        '2' => '#FFA500', // Naranja para prioridad 2
                                        '3' => '#FFFF00', // Amarillo para prioridad 3
                                        '4' => '#008000', // Verde para prioridad 4
                                        default => '#FFF1', // Blanco como valor por defecto
                                    };
                                    $set('color', $color);
                                }),

                            Forms\Components\ColorPicker::make('color')
                                ->label('Prioridad')
                                ->disabled() // Deshabilitado para que sea solo de lectura
                                ->columnSpan(1)->extraAttributes(['style' => 'pointer-events: none; width: 0px; height: 0px; border-radius: 0px;'])
                                ->reactive(),
                            Forms\Components\Textarea::make('recomendaciones_medico')
                                ->label('Recomendaciones')
                                ->placeholder('Recomendaciones Médico')
                                ->required()
                                ->columnSpan(3),
                            Forms\Components\Textarea::make('notas_medico')
                                ->label('Notas')
                                ->placeholder('Notas Médico')
                                ->required()
                                ->columnSpan(3),
                        ])->columns(6),

                        Fieldset::make('Gestión de Recursos')->schema([
                            Forms\Components\Select::make('ambulancia_id')
                                ->prefixIcon('heroicon-o-truck')
                                ->label('Cod. Ambulancia')
                                ->required()
                                ->columnSpan(3),
                            Forms\Components\TextInput::make('dui')
                                ->prefixIcon('heroicon-o-identification')
                                ->label('DUI motorista')
                                ->placeholder('0000000-0')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),
                            Forms\Components\CheckboxList::make('estado_ambulancia')
                                ->required()
                                ->options([
                                    'AR' => 'AR',
                                    'E' => 'E',
                                    'EL' => 'EL',
                                    'EA' => 'EA',
                                    'EC' => 'EC',
                                    'EE' => 'EE',
                                    'ED' => 'ED',
                                    'D' => 'D',
                                ])/*->descriptions([
                                    'AR' => 'Fecha'.$fecha,
                                    'E' => 'E',
                                    'EL' => 'EL',
                                    'EA' => 'EA',
                                    'EC' => 'EC',
                                    'EE' => 'EE',
                                    'ED' => 'ED',
                                    'D' => 'D',
                                ])*/->columns(8)
                                ->columnSpanFull(),
                            Forms\Components\Repeater::make('signos_vitales_gestor')
                                ->schema([
                                    Select::make('Signo Vital')
                                        ->options([
                                            'TA' => 'TA',
                                            'FC' => 'FC',
                                            'PR' => 'PR',
                                            'TEMP' => 'TEMP',
                                            'SAT02' => 'SAT02',
                                            'HGT' => 'HGT',
                                            'SG' => 'SG',
                                            'STR' => 'STR',
                                            'ESTADO E/I' => 'ESTADO E/I',
                                        ])
                                        ->required(),
                                    TextInput::make('Cantidad')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now())->visibleOn('Edit'),
                                ])->defaultItems(2)
                                ->addActionLabel('Agregar Signo Vital')
                                ->deletable(false)
                                ->reorderable(false)
                                ->collapsible()
                                ->grid(2)
                                //->addActionAlignment(Alignment::Start)
                                ->columns(2)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('centro_destino')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Centro de Destino')
                                ->required(),
                            Forms\Components\Select::make('codigos_actuacion_ambu')
                                ->label('Código de Actuación de Ambulancia')
                                ->prefixIcon('heroicon-o-exclamation-circle')
                                ->columnSpan(3)
                                ->required(),
                            Forms\Components\Textarea::make('notas_gestor')
                                ->placeholder('Notas Gestor')
                                ->label('Notas')
                                ->required()
                                ->columnSpanFull(),
                            /*
                            Forms\Components\Select::make('centro_origen')
                                ->required(),

                            Forms\Components\Textarea::make('requerimientos_especiales')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('medico_presenta')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('numero_presenta')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('medico_recibe')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('numero_recibe')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('signos_vitales_medicos')
                                ->required()
                                ->columnSpanFull(),*/
                        ])->columns(6),
                    ])->columnSpan(span: 3),
                Section::make('Actualizaciones') //->visibleOn('Edit')
                    ->schema([
                        Forms\Components\TextInput::make('created_at')
                            ->label('Hora de creación')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_id')
                            ->prefixIcon('heroicon-o-user')
                            ->label('Creado Por')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('prioridad')
                            ->prefixIcon('heroicon-o-exclamation-triangle')
                            ->required()
                            ->maxLength(255),
                    ])->columnSpan(1),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nu_caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('te_alertante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo_literal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_alertante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombres_paciente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellidos_paciente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('edad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sexo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dirección_operador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('diagnostivo_presuntivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioridad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dui')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_ambulancia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('centro_destino')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigos_actuacion_ambu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('centro_origen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medico_presenta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_presenta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('medico_recibe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_recibe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCasos::route('/'),
            'create' => Pages\CreateCaso::route('/create'),
            'edit' => Pages\EditCaso::route('/{record}/edit'),
        ];
    }
}

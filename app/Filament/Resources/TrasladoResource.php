<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrasladoResource\Pages;
use App\Filament\Resources\TrasladoResource\RelationManagers;
use App\Models\Caso;
use App\Models\Traslado;
use Filament\Forms;
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

class TrasladoResource extends Resource
{
    protected static ?string $model = Caso::class;
    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: TRASLADO';
    protected static ?string $navigationLabel = 'Traslado';
   // protected static ?string $navigationIcon = 'healthicons-o-ambulance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Traslado')
                    ->schema([
                        Fieldset::make('INFORMACIÓN CASO')->schema([
                            Forms\Components\Select::make('tipo_caso')
                                ->prefixIcon('heroicon-o-folder-open')
                                ->label('Tipo de Caso')
                                ->default('Traslado')
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
                                ->tel('8')
                                ->columnSpan('2')
                                ->required()
                                ->maxLength(255),
                        ])->columns(6),
                        Fieldset::make('Datos de Paciente')->schema([
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
                            Forms\Components\ToggleButtons::make('sexo')
                                ->columnSpan('1')
                                ->options([
                                    'M' => 'M',
                                    'F' => 'F'
                                ])
                                ->columns(2)
                                ->required(),
                            Forms\Components\Textarea::make('dirección_operador')
                                ->placeholder('Dirección dada por alertante')
                                ->columnSpan('3')
                                ->label('Dirección')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('diagnostivo_presuntivo')
                                ->label('Diagnostico Presuntivo')
                                ->placeholder('Diagnostico Presuntivo')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),

                        ])->columns(6),
                        Fieldset::make('Datos de Paciente')->schema([
                            Forms\Components\TextInput::make('centro_destino')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Centro de Destino')
                                ->placeholder('Ingrese Centro de Destino'),
                            Forms\Components\TextInput::make('centro_origen')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Centro de Destino')
                                ->placeholder('Ingrese Centro de Origen'),
                            Forms\Components\Select::make('centro_destino')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Centro de Destino')
                                ->options([
                                    'Hospital San Salvador' => 'Hospital San Salvador'
                                ])
                                ->required(),
                            Forms\Components\Select::make('centro_origen')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Centro de Destino')
                                ->options([
                                    'Hospital San Salvador' => 'Hospital San Salvador'
                                ])
                                ->required(),
                            Forms\Components\Textarea::make('requerimientos_especiales')
                                ->placeholder('Describa si el paciente requiero tratos especiales')
                                ->label('Requerimientos Especiales')
                                ->required()
                                ->columnSpanFull(),
                        ])->columns(6),
                        Fieldset::make('Datos de Médico')->schema([
                            Forms\Components\TextInput::make('medico_presenta')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Medico que Presenta')
                                ->placeholder('Ingrese nombre de Médico o Encargado'),

                            Forms\Components\TextInput::make('medico_recibe')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Medico que Recibe')
                                ->placeholder('Ingrese nombre de Médico o Encargado'),
                            Forms\Components\TextInput::make('numero_recibe')
                                ->columnSpan(3)
                                ->prefixIcon('heroicon-o-building-office-2')
                                ->label('Número de contacto que recibe')
                                ->placeholder('0000-0000'),
                        ])->columns(6),
                        Fieldset::make('Información Médico')->schema([
                            Forms\Components\Textarea::make('diagnostivo_presuntivo')
                                ->columnSpan(3)
                                ->label('Diagnostico Presuntivo')
                                ->placeholder('Ingrese nombre de Médico o Encargado'),
                            Forms\Components\Select::make('tipo_ambulancia')
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
                                ->label(label: 'Prioridad')
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
                                ->beforeStateDehydrated(function ($state, callable $set) {
                                    // Cambia el color del ColorPicker basado en la prioridad seleccionada
                                    $color = match ($state) {
                                        '1' => '#FF0000', // Rojo para prioridad 1
                                        '2' => '#FFA500', // Naranja para prioridad 2
                                        '3' => '#FFFF00', // Amarillo para prioridad 3
                                        '4' => '#008000', // Verde para prioridad 4
                                        default => '#FFF1', // Blanco como valor por defecto
                                    };
                                    $set('color', $color);
                                })
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
                                ->label('Codigo de Ambulancia')
                                ->options([
                                    'A192' => 'A192'
                                ])
                                ->required()
                                ->columnSpan(3),
                            Forms\Components\Textarea::make('notas_gestor')
                                ->placeholder('Notas Gestor')
                                ->label('Notas')
                                ->required()
                                ->columnSpan(3),
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
                            Forms\Components\Repeater::make('signos_vitales_gestor')->helperText('Ingreso de Signos vitales')
                                ->schema([
                                    TextInput::make(name: 'TA')->label('Signo  Vital')->required()->placeholder('0000')->default('TA'),
                                    TextInput::make('Cantidad1')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'FC')->label('Signo  Vital')->required()->placeholder('0000')->default('FC'),
                                    TextInput::make('Cantidad2')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'FR')->label('Signo  Vital')->required()->placeholder('0000')->default('FR'),
                                    TextInput::make('Cantidad3')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'TEMP')->label('Signo  Vital')->required()->placeholder('0000')->default('TEMP'),
                                    TextInput::make('Cantidad4')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'SAT 02')->label('Signo  Vital')->required()->placeholder('0000')->default('SAT 02'),
                                    TextInput::make('Cantidad5')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'HGT')->label('Signo  Vital')->required()->placeholder('0000')->default('HGT'),
                                    TextInput::make('Cantidad6')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'SG')->label('Signo  Vital')->required()->placeholder('0000')->default('SG'),
                                    TextInput::make('Cantidad7')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'STR')->label('Signo  Vital')->required()->placeholder('0000')->default('STR'),
                                    TextInput::make('Cantidad8')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),

                                    TextInput::make(name: 'ESTADO E/I')->label('Signo  Vital')->required()->placeholder('0000')->default('ESTADO E/I'),
                                    TextInput::make('Cantidad9')->required()->placeholder('0000'),
                                    DateTimePicker::make('fecha')->required()->readOnly()->default(now()),
                                ])->defaultItems(1)
                                ->addActionLabel('Agregar Signo Vital')
                                ->deletable(false)
                                ->reorderable(false)
                                ->collapsible()
                                ->grid(1)
                                //->addActionAlignment(Alignment::Start)
                                ->columns(3)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('codigos_actuacion_ambu')
                                ->label('Código de Actuación de Ambulancia')
                                ->prefixIcon('heroicon-o-exclamation-circle')
                                ->columnSpan(3)
                                ->options([
                                    'Codigo Actuacion' => 'Codigo Actuacion'
                                ])
                                ->required(),
                        ])->columns(6),

                    ])->columns(6)->columnSpan(3),
                Section::make('Actualizaciones') //->visibleOn('Edit')
                    ->schema([
                        Forms\Components\TextInput::make('created_at')
                            ->label('Hora de creación')
                            ->default(now())
                            ->live(200)
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_id')
                            ->default('Mejia')
                            ->prefixIcon('heroicon-o-user')
                            ->label('Creado Por')
                            ->required()
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
                //
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
            'index' => Pages\ListTraslados::route('/'),
            'create' => Pages\CreateTraslado::route('/create'),
            'edit' => Pages\EditTraslado::route('/{record}/edit'),
        ];
    }
}

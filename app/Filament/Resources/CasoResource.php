<?php

namespace App\Filament\Resources;

use app\Filament\Resources\CasoResource\RelationManagers\Llamadas2RelationManager;
use App\Filament\Resources\CasoResource\Pages;
use App\Filament\Resources\CasoResource\RelationManagers;
use App\Filament\Resources\CasoResource\RelationManagers\LlamadasRelationManager;
use App\Models\Ambulancia;
use App\Models\Caso;
use App\Models\CentroSanitario;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\EstadoCaso;
use App\Models\Instituciones;
use App\Models\Llamadas;
use App\Models\MotivoExclusion;
use App\Models\ResAtencion;
use App\Models\traslado_noefectivo;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use app\Filament\Resources\CasoLlamadasRelationManagerResource;
use Filament\Forms\Components\Select;
use PHPUnit\Framework\Attributes\Small;
use Filament\Tables\Columns\TextColumn;
use DiscoveryDesign\FilamentGaze\Forms\Components\GazeBanner;

class CasoResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Caso::class;
    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: ATENCIÓN PH';
    protected static ?string $navigationLabel = 'Atención PH';
    protected static string $relationship = 'llamadas'; // Relación en el modelo Caso.

    protected static ?string $navigationIcon = 'healthicons-o-accident-and-emergency';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                GazeBanner::make()->pollTimer(10)->lock()->hideOnCreate()->columnSpanFull(),
                Section::make('Atención Pre-Hospitalaria')->schema([
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
                                ->options(Llamadas::limit(1)->pluck('llamada_correlativo', 'id')) // Obtiene las opciones.
                                ->searchable()
                                ->optionsLimit(1)
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
                    Fieldset::make('Datos del Paciente')
                        ->schema([
                            Forms\Components\TextInput::make('tipo_caso')
                                ->label('Tipo de Caso')
                                ->readOnly()
                                ->prefixIcon('heroicon-o-archive-box')
                                ->columnSpanFull()
                                ->default('Asistencia PH'),
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
                                ->columns(2)
                            ,
                            Forms\Components\Textarea::make('dirección')

                                ->placeholder('Dirección del incidente')
                                ->columnSpan(4),
                            Forms\Components\Textarea::make('puntos_referencia')

                                ->placeholder('Puntos de Referencia')
                                ->columnSpan(4),
                            Forms\Components\Select::make('departamento')

                                ->columnSpan(4)
                                ->reactive()
                                ->prefixIcon('heroicon-o-globe-americas')
                                ->options(Departamento::all()->pluck('name', 'name'))->searchable(),
                            Forms\Components\Select::make('distrito')

                                ->prefixIcon('heroicon-o-globe-americas')
                                ->reactive()
                                ->columnSpan(4)
                                ->options(Distrito::all()->pluck('name', 'name'))->searchable(),
                        ])->columns(8)->columnSpanFull(),
                    Fieldset::make('Medidas para la atención')
                        ->schema([
                            Forms\Components\Select::make('tap')
                                ->options([
                                    'Accidente de Transito' => 'Accidente de Transito',
                                    'Quemaduras' => 'Quemaduras',
                                ])
                                ->prefixIcon('healthicons-o-health-literacy')
                                ->columnSpan(4),
                            Forms\Components\Select::make('tap1')
                                ->prefixIcon('healthicons-o-health-literacy')
                                ->columnSpan(4)
                                ->options([
                                    'Motovía' => 'Motovía',
                                    'Quemaduras de primer grado' => 'Quemaduras de primer grado',
                                ]),
                            Fieldset::make('Medidas para la atención')
                                ->schema([
                                    Forms\Components\TextInput::make('plan_experto')
                                        ->columnSpan(6)
                                        ->maxLength(255),
                                ])->columnSpan(6),
                            Fieldset::make('Medidas para la atención')
                                ->schema([
                                    Forms\Components\Select::make('prioridad')
                                        ->prefixIcon('heroicon-o-exclamation-triangle')
                                        ->options([
                                            '1' => '1',
                                            '2' => '2',
                                            '3' => '3',
                                            '4' => '4',
                                        ])
                                        ->placeholder('Opción')
                                        ->columnSpan(1)
                                        ->reactive()
                                        ->live()
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
                                        ->label('Color')
                                        ->live(100)
                                        ->columnSpan(1)->extraAttributes(['style' => 'pointer-events: none; width: 0px; height: 0px; border-radius: 0px;'])
                                        ->reactive(),
                                    Forms\Components\Textarea::make('antecedentes')
                                        ->placeholder('Ingrese antecedentes si es necesario')
                                        ->columnSpanFull(),
                                    Forms\Components\Textarea::make('enfermedades')
                                        ->placeholder('Ingrese enfermedades si es necesario')
                                        ->columnSpanFull(),
                                    Forms\Components\Toggle::make('asegurado')
                                    ,
                                ])->columnSpan(2),
                        ])->columns(8),
                    Fieldset::make('Coordinación con Gestores de Recursos')
                        ->schema([
                            Forms\Components\Select::make('institucion')->prefixIcon('healthicons-o-accident-and-emergency')
                                ->options(Instituciones::all()->pluck('name', 'name'))->default('SEM132')->searchable(),
                            Forms\Components\Select::make('institucion_apoyo')->prefixIcon('healthicons-o-emergency-post')
                                ->options(Instituciones::all()->pluck('name', 'name'))->searchable(),
                            Forms\Components\Textarea::make('notas')
                                ->placeholder('Notas/Observaciones')
                                ->columnSpanFull(),
                        ])->columnSpanFull(),
                    Fieldset::make('Gestión de Recursos')
                        ->schema([
                            Forms\Components\Select::make('via_transporte')
                                ->columnSpan(1)
                                ->prefixIcon('heroicon-o-archive-box')
                                ->options([
                                    'Terrestre' => 'Terrestre',
                                    'Acuatico' => 'Acuatico',
                                    'Aereo' => 'Aereo'
                                ]),
                            Forms\Components\Select::make('tipo_unidad')
                                ->columnSpan(1)
                                ->native()
                                ->prefixIcon('heroicon-o-archive-box')
                                ->options(['A' => 'A', 'B' => 'B', 'C' => 'C']),
                            Forms\Components\Select::make('recurso_asignado')
                                ->columnSpan(1)
                                ->searchable()
                                ->prefixIcon('healthicons-o-ambulance')
                                ->options(Ambulancia::all()->pluck('unidad', 'unidad')),
                            Forms\Components\Repeater::make('estado_recurso')
                                ->schema([
                                    Forms\Components\Checkbox::make('AR')
                                        ->label('AR')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(callback: fn($state, callable $set) => $set('fecha_ar', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('E')
                                        ->label('E')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_e', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('EL')
                                        ->label('EL')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_el', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('EA')
                                        ->label('EA')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_ea', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('EC')
                                        ->label('EC')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_ec', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('EE')
                                        ->label('EE')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_ee', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('ED')
                                        ->label('ED')
                                        ->reactive()
                                        ->live()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_ed', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),
                                    Forms\Components\Checkbox::make('D')
                                        ->label('D')
                                        ->live()
                                        ->reactive()
                                        ->afterStateUpdated(fn($state, callable $set) => $set('fecha_d', $state ? Carbon::now()->format('Y-m-d H:i:s') : null)),

                                    ///FECHAS y HORAS
                                    Forms\Components\Textarea::make('fecha_ar')
                                        ->label('')
                                        ->live()
                                        ->autosize()
                                        ->readOnly()->placeholder('Fecha AR'),
                                    Forms\Components\Textarea::make('fecha_e')
                                        ->label('')
                                        ->live()
                                        ->autosize()
                                        ->readOnly()->placeholder('Fecha E'),
                                    Forms\Components\Textarea::make('fecha_el')
                                        ->label('')
                                        ->live()
                                        ->autosize()
                                        ->readOnly()->placeholder('Fecha EL'),
                                    Forms\Components\Textarea::make('fecha_ea')
                                        ->label('')
                                        ->autosize()
                                        ->live()
                                        ->readOnly()
                                        ->placeholder('Fecha EA'),
                                    Forms\Components\Textarea::make('fecha_ec')
                                        ->label('')
                                        ->autosize()
                                        ->live()
                                        ->readOnly()
                                        ->placeholder('Fecha EC'),
                                    Forms\Components\Textarea::make('fecha_ee')
                                        ->live()
                                        ->label('')
                                        ->autosize()
                                        ->readOnly()->placeholder('Fecha EE'),
                                    Forms\Components\Textarea::make('fecha_ed')
                                        ->label('')
                                        ->autosize()
                                        ->live()
                                        ->readOnly()
                                        ->placeholder('Fecha ED'),
                                    Forms\Components\Textarea::make('fecha_d')
                                        ->label('')
                                        ->live()
                                        ->autosize()
                                        ->readOnly()->placeholder('Fecha D'),
                                ])->columns(8)->columnSpanFull()->addActionLabel('Agregar Fila')
                                ->reorderable(false)
                                ->deletable(false),
                            Forms\Components\Textarea::make('notas_gestor_recurso')
                                ->label('Notas')
                                ->placeholder('Escribe aquí cualquier nota adicional...')
                                ->columnSpanFull()
                                ->autosize(),
                            Forms\Components\TextInput::make('usuario')
                                ->label('Usuario')
                                ->hidden()
                                ->reactive()
                                ->default(fn() => Auth::user()->name),
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('unidad_salud_traslado')
                                        ->columnSpan(1)
                                        ->prefixIcon('heroicon-o-building-office-2')
                                        ->options(CentroSanitario::all()->pluck('name', 'name')),
                                    Forms\Components\Select::make('unidad_salud_sugerido')
                                        ->prefixIcon('heroicon-o-building-office-2')
                                        ->columnSpan(1)
                                        ->options(CentroSanitario::all()->pluck('name', 'name')),
                                ])
                        ])->columns(3),
                    Fieldset::make('Información de la Atención')
                        ->schema([
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
                        ])
                        ->columnSpanFull(),
                    Fieldset::make('Información de la Atención')
                        ->schema([
                            Forms\Components\Select::make('efectividad')
                                ->reactive()
                                ->columnSpan(1)
                                ->prefixIcon('heroicon-o-check-badge')
                                ->options(['Si' => 'Si', 'No' => 'No']), // Especifica opciones como array asociativo
                            Forms\Components\Select::make('razon_noefectivo')
                                ->hidden(fn(callable $get) => $get('efectividad') != 'No') // Muestra solo si "efectividad" es "No"
                                ->options(fn() => traslado_noefectivo::all()->pluck('name', 'name')) // Consulta diferida
                                ->prefixIcon('heroicon-o-rectangle-stack')
                                ->columnSpan(1)
                                ->reactive()
                                ->label('Razón del traslado no efectivo'),
                            Forms\Components\Select::make('exclusion')
                                ->prefixIcon('heroicon-o-check-badge')
                                ->columnSpan(1)
                                ->reactive()
                                ->options(['Si' => 'Si', 'No' => 'No']), // Especifica opciones como array asociativo
                            Forms\Components\Select::make('motivo_exclusion')
                                ->label('Motivos de exclusión')
                                ->prefixIcon('heroicon-o-rectangle-stack')
                                ->columnSpan(1)
                                ->hidden(fn(callable $get) => $get('exclusion') != 'Si') // Muestra solo si "exclusion" es "Si"
                                ->options(fn() => MotivoExclusion::all()->pluck('name', 'name')), // Consulta diferida
                            Forms\Components\Textarea::make('notas_gestor')
                                ->placeholder('Notas/Observaciones')
                                ->columnSpanFull(),
                        ])
                        ->columnSpan('full')->columns(4),
                    Fieldset::make('Información de la Atención')
                        ->schema([
                            Fieldset::make('Cierre de la Atención')
                                ->schema([
                                    Forms\Components\Select::make('cie10')
                                        ->label('CIE10')
                                        // ->options(::all()->pluck('name', 'name'))
                                        ->options([
                                            'A00 COLERA' => 'A00 COLERA',
                                            'A01 COLERA DEBIDO A VIBRIO ' => 'A01 COLERA DEBIDO A VIBRIO ',
                                        ])
                                        ->prefixIcon('healthicons-o-clinical-fe'),
                                    Forms\Components\TextInput::make('juicio_clinico1')
                                        ->prefixIcon('healthicons-o-justice')
                                        ->columnSpanFull()
                                        ->placeholder('Descripción')
                                        ->label('Juicio Clinico 1'),
                                    Forms\Components\TextInput::make('juicio_clinico2')
                                        ->prefixIcon('healthicons-o-justice')
                                        ->columnSpanFull()
                                        ->label('Juicio Clinico 2')
                                        ->placeholder('Descripción'),
                                    Forms\Components\TextInput::make('juicio_clinico3')
                                        ->prefixIcon('healthicons-o-justice')
                                        ->columnSpanFull()
                                        ->placeholder('Descripción')
                                        ->label('Juicio Clinico 3'),
                                ])->columnSpanFull(),
                            Forms\Components\Select::make('condicion_paciente')
                                ->prefixIcon('healthicons-o-sling')->options([
                                        'Estable' => 'Estable',
                                        'Embarazada crítico' => 'Embarazada crítico',
                                        'Neonato critico' => 'Neonato critico',
                                        'Niño crítico' => 'Niño crítico',
                                        'Adolescente critico' => 'Adolescente critico',
                                        'Adulto crítico' => 'Adulto crítico',
                                    ])
                                ->nullable(),
                            Forms\Components\Select::make('paciente_critico')
                                ->prefixIcon('healthicons-o-heart-cardiogram')
                                ->options([
                                    'Paciente sin ventilacion mecanica sin aminas' => 'Paciente sin ventilacion mecanica sin aminas',
                                    'Paciente sin ventilación mecánica con aminas' => 'Paciente sin ventilación mecánica con aminas',
                                    'Paciente con ventilación mecánica con aminas' => 'Paciente con ventilación mecánica con aminas',
                                    'Paciente con ventilación mecánica sin aminas' => 'Paciente con ventilación mecánica sin aminas',
                                    'No aplica' => 'No aplica',
                                ])
                                ->nullable(),
                            Forms\Components\Select::make('resolucion_atencion')
                                ->prefixIcon('healthicons-o-crisis-response-center-person')
                                ->reactive()
                                ->options(ResAtencion::all()->pluck('name', 'name'))
                                ->nullable(),
                            Forms\Components\Select::make('fallecimiento')
                                ->nullable()
                                ->reactive()
                                ->hidden(fn(callable $get) => $get('resolucion_atencion') != 'Fallecido')
                                ->prefixIcon('healthicons-o-death-alt')
                                ->options([
                                    'Antes de llegar al Lugar' => 'Antes de llegar al Lugar',
                                    'Durante el Traslado' => 'Durante el Traslado',
                                    'Durante Entrega' => 'Durante Entrega',
                                ]),
                            Fieldset::make('En caso de Fallecimiento')->hidden(fn(callable $get) => $get('resolucion_atencion') != 'Fallecido')
                                ->schema([
                                    Forms\Components\ToggleButtons::make('acta_defuncion')
                                        ->label('¿Se realizó Acta de defunción?')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Antes de llegar al Lugar')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No'
                                        ])->inline(),
                                    Forms\Components\ToggleButtons::make('medicina_legal')
                                        ->label('¿Inspeccionado por Medicina Legal?')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Antes de llegar al Lugar')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No'
                                        ])->inline(),
                                    Forms\Components\ToggleButtons::make('retorno_origen')
                                        ->label('¿Retorno a lugar de Origen?')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Durante el Traslado')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No'
                                        ])->inline(),
                                    Forms\Components\ToggleButtons::make('entregado_destino')
                                        ->label('¿Entregado a su Destino?')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Durante el Traslado')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No'
                                        ])->inline(),
                                    Forms\Components\TextInput::make('nombre_recibio')->maxLength(255)
                                        ->label('Nombre del Receptor')
                                        ->columnSpanFull()
                                        ->prefixIcon('heroicon-o-user')
                                        ->placeholder('Nombre del que recibio')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Durante el Traslado'),
                                    Forms\Components\ToggleButtons::make('aceptado_destino')
                                        ->hidden(fn(callable $get) => $get('fallecimiento') != 'Durante Entrega')
                                        ->options([
                                            'Si' => 'Si',
                                            'No' => 'No'
                                        ])->inline(),
                                ])->columnSpan(2),
                            Forms\Components\Textarea::make('notas_medicos')
                                ->label('Notas')
                                ->placeholder('Notas/Observaciones')
                                ->columnSpan(2),
                        ])->columnSpanFull()->columns(4),
                ])->columnSpanFull()->columns(4),
            ])->columns(8);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->paginated([10, 25, 50, 100])
            ->columns([

                /*
                Tables\Columns\TextColumn::make('llamadas2.llamada_correlativo')
                    ->label('Llamadas Asociadas')
                    ->sortable()
                    ->alignJustify()
                    ->searchable(),*/

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
            ->filters([
                //
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('llamadas2');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }
    public static function relationManagers(): array
    {
        return [
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LlamadasResource\Pages;
use App\Models\Caso;
use App\Models\Ambulancia;
use App\Models\CentroSanitario;
use App\Models\Llamadas;
use App\Models\TipoCaso;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Filament\Forms\Components\Actions;
use Filament\Support\View\Components\Modal;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Placeholder;
use app\Filament\Resources\CasoResource\RelationManagers\Llamadas2RelationManager;
use DiscoveryDesign\FilamentGaze\Forms\Components\GazeBanner;
use Illuminate\Support\Facades\Auth;

//Control de vistas
Modal::closeButton(false);
Modal::closedByClickingAway(false);
class LlamadasResource extends Resource
{
    protected static ?string $model = Llamadas::class;
    protected static ?string $label = 'Llamadas';
    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Casos';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()
                    ->collapsible()
                    ->description(' ')
                    ->aside()
                    ->schema([
                        Forms\Components\Placeholder::make('Datos de Llamada')
                            ->columnSpan(2)
                            ->content(
                                'Datos Presentes en llamada'
                                /*fn(callable $get): string =>
                                sprintf(
                                    "\n Hora de Creación: %s\n Médico APH: %s",
                                    $get('hora_creacion') ?? 'Sin Hora de Creación',
                                    $get('medico_aph') ?? 'Sin Médico APH'
                                )*/
                            ),
                        Forms\Components\TextInput::make('usuario')
                            ->label('Usuario')
                            ->reactive()
                            ->readOnly()
                            ->columnSpan(2)
                            ->required()
                            ->prefixIcon('heroicon-o-user')
                            ->default(fn() => Auth::user()->name),
                    ])->columnSpan('full')->columns(4),
                Forms\Components\Section::make()->hidden()
                    ->schema([
                        Fieldset::make('Tipo de Caso')->hidden()
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\Checkbox::make('Asistencia')->hintIcon('heroicon-o-user')->inline(),
                                        Forms\Components\Checkbox::make('Traslado')->hintIcon('heroicon-o-user')->inline(),
                                        Forms\Components\Checkbox::make('Informativa')->hintIcon('heroicon-o-user')->inline(),
                                        Forms\Components\Checkbox::make('No pertinente')->hintIcon('heroicon-o-user')->inline(),
                                    ]),
                            ]),
                    ])
                    ->collapsed(),
                Forms\Components\TextInput::make('llamada_correlativo')
                    ->required()
                    ->readOnly()
                    ->visibleOn(operations: 'edit')
                    ->label('Correlativo de Llamada')
                    ->autofocus()
                    ->prefixIcon('heroicon-o-information-circle')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('llamada_correlativo')
                    ->required()
                    ->readOnly()
                    ->visibleOn(operations: 'view')
                    ->label('Correlativo de Llamada')
                    ->autofocus()
                    ->prefixIcon('heroicon-o-information-circle')
                    ->columnSpanFull(),
                Fieldset::make('Datos de Llamada')
                    ->schema([
                        Forms\Components\TextInput::make('telefono_alertante')
                            ->required()
                            ->readOnly()
                            ->label('Telefono del alertante')
                            ->prefixIcon('heroicon-o-phone')
                            ->default('6146 8848')
                            ->placeholder('0000-0000')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('medico_aph')
                            ->required()
                            ->readOnly()
                            ->default('Brandon Mejia')
                            ->placeholder('Médico APH de Turno')
                            ->label('Médico APH de turno')
                            ->prefixIcon('healthicons-o-doctor')
                            ->columnSpan(1),
                        /*   Forms\Components\Placeholder::make('placeholder_medico_aph')
                               ->label('Médico APH de Turno')
                               ->content(fn(callable $get) => $get('medico_aph')),*/
                        Forms\Components\DateTimePicker::make('hora_creacion')
                            ->label('Hora de Creación')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar-date-range')
                            ->readOnly()
                            ->default(Carbon::now())->withoutSeconds()
                            ->columnSpan(1),
                        /*  Forms\Components\Placeholder::make('placeholder_hora_creacion')
                              ->label('Hora de Apertura')
                              ->content(fn(callable $get) => $get('hora_creacion')),*/

                        /*Forms\Components\Placeholder::make('placeholder_telefono_alertante')
                            ->label('Telefono Alertante')
                            ->content(fn(callable $get) => $get('telefono_alertante')),*/
                    ])->columns(3),
                Fieldset::make('Datos Generales de la Llamada')
                    ->schema([
                        Forms\Components\TextInput::make('nombre_alertante')
                            ->required()
                            ->prefixIcon('heroicon-o-user-circle')
                            ->placeholder('Ingrese nombre de alertante')
                            ->label('Nombre del Alertante')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('motivo_literal')
                            ->required()
                            ->prefixIcon('heroicon-o-clipboard')
                            ->placeholder('Ingrese el motivo literal de la llamada')
                            ->label('Motivo Literal')
                            ->columnSpan(2),
                        Forms\Components\Fieldset::make()->schema(
                            [
                                Forms\Components\Radio::make('tipo_caso')
                                    ->required()
                                    //->prefixIcon('heroicon-o-archive-box')
                                    ->options(TipoCaso::all()->pluck('name', 'name'))
                                    ->hintIcon('heroicon-o-clipboard')
                                    // Opciones como [id => name]
                                    ->reactive() // Permite que las actualizaciones afecten otros campos
                                    ->columnSpanFull()
                            ]
                        )->columnSpan(1),
                        Forms\Components\Select::make('opcion_pertinente')
                            ->required()
                            ->hidden(fn(callable $get) => $get('tipo_caso') != TipoCaso::where('name', 'No pertinente')->value('name')) // Compara con el ID del tipo "Informativa"
                            ->options([
                                'Broma' => 'Broma',
                                'Errores' => 'Errores',
                                'Desvío' => 'Desvío',
                            ])
                            ->label('Opciones del Pertinente')
                            ->prefixIcon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->columnSpan(2),
                        Forms\Components\Select::make('opcion_informativa')
                            ->required()
                            ->hidden(fn(callable $get) => $get('tipo_caso') != TipoCaso::where('name', 'Informativa')->value('name')) // Compara con el ID del tipo "Informativa"
                            ->options([
                                'Información Sanitaria' => 'Información Sanitaria',
                                'Teleorientación Médica' => 'Teleorientación Médica',
                                'Felicitaciones' => 'Felicitaciones',
                                'Reclamos' => 'Reclamos',
                                'No relacionadas a Salud' => 'No relacionadas a Salud',
                            ])
                            ->label('Opciones Informativas')
                            ->prefixIcon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->columnSpan(2),
                        Fieldset::make('Datos de Ambulancia')
                            ->hidden(fn(callable $get) => $get('tipo_caso') != TipoCaso::where('name', 'Autorización de Ambulancia a Préstamo')->value('name')) // Compara con el ID del tipo "Informativa"
                            ->schema([
                                Forms\Components\Select::make('lugar_origen')
                                    ->label('Lugar de Origen')
                                    ->live()
                                    ->placeholder('Origen')
                                    ->prefixIcon('heroicon-o-home-modern')
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        CentroSanitario::all()->pluck('name', 'name'),
                                    )
                                    ->searchable()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('lugar_destino')
                                    ->label('Lugar de Destino')
                                    ->placeholder('Destino')
                                    ->prefixIcon('heroicon-o-home-modern')
                                    ->live()
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        CentroSanitario::all()->pluck('name', 'name'),
                                    )
                                    ->searchable()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('cod_ambulancia')
                                    ->placeholder('Ambulancia')
                                    ->label('Codigo de Ambulancia')
                                    ->prefixIcon('healthicons-o-ambulance')
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        Ambulancia::all()->pluck('unidad', 'unidad'),
                                    )
                                    ->searchable()
                                    ->columnSpan(2),
                            ])->columns(2)->columnSpan(2),
                        Fieldset::make('Datos de Traslado')
                            ->hidden(fn(callable $get) => $get('tipo_caso') != TipoCaso::where('name', 'Traslado')->value('name')) // Compara con el ID del tipo "Informativa"
                            ->schema([
                                Forms\Components\Select::make('lugar_solicitante')
                                    ->label('Hospital / Unidad de Salud Solicitante')
                                    ->live()
                                    ->placeholder('Origen')
                                    ->prefixIcon('heroicon-o-home-modern')
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        CentroSanitario::all()->pluck('name', 'name'),
                                    )
                                    ->searchable()
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('diagnostico_traslado')
                                    ->required()
                                    ->autosize()
                                    ->label('Diagnostico del traslado')
                                    ->placeholder('Ingrese la descripción de la llamada')
                                    ->columnSpan(1),
                                Forms\Components\Radio::make('aplica_traslado')
                                    ->label('¿Aplica Traslado?')
                                    ->reactive()
                                    ->options([
                                        'Si' => 'Si',
                                        'No' => 'No'
                                    ]),
                                Forms\Components\Textarea::make('justificacion_traslado')
                                    ->hidden(fn(callable $get) => $get('aplica_traslado') != 'No')
                                    ->label('Justificación')
                                    ->placeholder('Ingrese la justificación del traslado')
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('motivo_traslado')
                                    ->label('Motivo')
                                    ->hidden(fn(callable $get) => $get('aplica_traslado') != 'Si')
                                    ->options([
                                        'Embarazo' => 'Embarazo',
                                        'Pediatrico' => 'Pediatrico',
                                        'Neonato' => 'Neonato',
                                        'Adulto Crítico' => 'Adulto Crítico',
                                    ])
                                    ->placeholder('Motivos de tralado')
                                    ->columnSpanFull(),
                            ])->columns(2)->columnSpan(2),
                        Forms\Components\Textarea::make('descripcion_caso')
                            ->required()
                            ->label('Descripción de Llamada')
                            ->placeholder('Ingrese la descripción de la llamada')
                            ->columnSpan(2),
                    ])->columns(3),

                Actions::make([
                    /* Actions\Action::make('crear_atencion')
                         ->color('danger')
                         ->icon('heroicon-o-plus')
                         ->action(function (array $data): mixed {
                             $tipocaso = fn(callable $get) => $get('tipo_caso');
                             // Verificar el tipo de caso
                             if ($data['tipo_caso'] === 'Asistencia') {
                                 // Redirigir al formulario de casos
                                 return redirect()->route('filament.resources.casos.create', [
                                     'llamada_id' => $data['id'], // Pasar el ID de la llamada si es necesario
                                 ]);
                             }
                             // Enviar notificación para otros casos
                             Notification::make()
                                 ->title('Llamada creada correctamente')
                                 ->success()
                                 ->icon('heroicon-o-check-circle')
                                 ->date(Carbon::now())
                                 ->persistent()
                                 ->send();

                             return null;
                         })
                         ->modalHeading('Crear Atención')
                         ->modalIcon('heroicon-o-plus-circle')
                         ->modalDescription('La atención se creará automáticamente al cerrar la llamada. Si cancela, regresará al formulario de llamada.')
                         ->modalSubmitActionLabel('Sí, crear atención'),*/
                    Actions\Action::make('asociar_llamada')
                        ->label('Asociar Llamada')
                        ->icon('heroicon-o-link')
                        ->color('success')
                        ->form([
                            Select::make('caso_id')
                                ->label('Seleccionar Caso')
                                ->searchable()
                                ->prefixIcon('heroicon-o-archive-box')
                                //->options(Caso::all()->max(5)->pluck('correlativo_caso', 'correlativo_caso')) // Muestra "nu_caso" como opciones
                                ->columnSpan(1),
                            Fieldset::make('Datos de Ambulancia')
                                ->schema([
                                    Select::make('correlativo_caso')
                                        ->searchable()
                                        ->relationship('caso')
                                        ->preload()
                                        //   ->options(Caso::all()->pluck('correlativo_caso', 'correlativo_caso'))
                                        ->label('Número de Caso')
                                        ->columnSpan(1)
                                        ->reactive(),
                                    TextInput::make('telefono_alertante')
                                        ->label('Teléfono del Alertante')
                                        ->columnSpan(1)
                                        ->reactive(),
                                    TextInput::make('nombre_alertante')
                                        ->label('Nombre del Alertante')
                                        ->columnSpan(1)
                                        ->reactive(),
                                    TextInput::make('motivo_literal')
                                        ->label('Motivo Literal')
                                        ->columnSpan(2)
                                        ->reactive(),
                                    TextInput::make('tipo_caso')
                                        ->label('Tipo de Caso')
                                        ->columnSpan(1)
                                        ->reactive(),
                                    TextInput::make('descripcion_caso')
                                        ->label('Descripción del Caso')
                                        ->columnSpan(3)
                                        ->reactive(),
                                ])->columns(3)
                        ])
                        ->stickyModalHeader() // Usamos un formulario deslizante
                        ->action(function (array $data): void {
                            // Implementar la lógica para aso
                            $llamadaId = $data['llamada_id'];
                            // Ejemplo de lógica: asociar llamada al recurso actual
                            //$this->record->update(['llamada_id' => $llamadaId]);
                            //   $this->record->update(['llamada_id' => $llamadaId]);

                            // Mensaje de éxito
                            $this->notify('success', 'Llamada asociada correctamente.');
                        }),
                ])->columnSpanFull()->alignRight(),
            ])->columns(4);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->paginated([10, 25, 50, 100])
            ->columns([
                Tables\Columns\TextColumn::make('llamada_correlativo')
                    ->prefix('#')
                    ->badge()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->alignCenter()
                    ->searchable()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('medico_aph')
                    ->alignCenter()
                    ->searchable()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono_alertante')
                    ->label('T. Alertante')
                    ->alignCenter()
                    ->searchable()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_alertante')
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('motivo_literal')
                    ->limit(20)
                    ->tap()
                    ->alignCenter()
                    ->searchable()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_caso')
                    ->badge()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->searchable()
                    ->alignCenter()
                    ->limit(20)
                    ->color(function ($record) {
                        $tipo_caso = $record->tipo_caso;
                        if ($tipo_caso === "Asistencia") {
                            return 'danger';
                        }
                        if ($tipo_caso === "Traslado") {
                            return 'warning';
                        }
                        if ($tipo_caso === "Evento") {
                            return 'amarillo';
                        }
                        if ($tipo_caso === "Autorización de Ambulancia a Préstamo") {
                            return 'success';
                        }
                        if ($tipo_caso === "Informativa") {
                            return 'primary';
                        }
                        if ($tipo_caso === "Consulta") {
                            return 'success';
                        }
                        if ($tipo_caso === "Emergencia") {
                            return 'danger';
                        }
                        if ($tipo_caso === "Otro") {
                            return 'gray';
                        }
                    })
                    ->icon(function ($record) {
                        $tipo_caso = $record->tipo_caso;
                        if ($tipo_caso === "Asistencia") {
                            return 'healthicons-o-accident-and-emergency';
                        }
                        if ($tipo_caso === "Traslado") {
                            return 'healthicons-o-mobile-clinic';
                        }
                        if ($tipo_caso === "Evento") {
                            return 'heroicon-o-star';
                        }
                        if ($tipo_caso === "Autorización de Ambulancia a Préstamo") {
                            return 'heroicon-o-truck';
                        }
                        if ($tipo_caso === "Informativa") {
                            return 'heroicon-o-document-text';
                        }
                        if ($tipo_caso === "Consulta") {
                            return 'heroicon-o-document-magnifying-glass';
                        }
                        if ($tipo_caso === "Emergencia") {
                            return 'heroicon-o-exclamation-triangle';
                        }
                        if ($tipo_caso === "Otro") {
                            return 'heroicon-o-chat-bubble-bottom-center-text';
                        }
                    })
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hora_creacion')
                    ->dateTime()
                    ->searchable()
                    ->alignCenter()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
                Tables\Actions\EditAction::make()
                    ->modelLabel('Llamada')->modalSubmitActionLabel('Cerrar Llamada')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->modalHeading('Editar Datos de Llamada')
                    ->modalDescription('Captura de Datos de llamada')
                    ->modalIcon('heroicon-o-document-duplicate')
                    ->modalAlignment('center')
                    ->modalCancelAction(false),
                Tables\Actions\ViewAction::make()
                    ->modelLabel('Llamada')->modalSubmitActionLabel('Cerrar Llamada')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading('Editar Datos de Llamada')
                    ->modalDescription('Captura de Datos de llamada')
                    ->modalIcon('heroicon-o-clipboard-document')
                    ->modalAlignment('center'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\AttachAction::make()
                        ->icon('heroicon-o-link')->color('primary'),
                    Tables\Actions\EditAction::make()->color('primary'),
                    Tables\Actions\ViewAction::make()->color('primary'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ])
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //   AuditsRelationManager::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('usuario', auth()->user()->name) // Filtrar por el usuario autenticado
            ->where('hora_creacion', '>=', Carbon::now()->subDay());
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLlamadas::route('/'),
            //  'create' => Pages\CreateLlamadas::route('/create'),
            //'edit' => Pages\EditLlamadas::route('/{record}/edit'),
        ];
    }
}

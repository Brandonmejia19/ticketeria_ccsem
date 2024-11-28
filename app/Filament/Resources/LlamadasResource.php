<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LlamadasResource\Pages;
use App\Filament\Resources\LlamadasResource\RelationManagers;
use App\Models\Caso;
use App\Models\Ambulancia;
use App\Models\CentroSanitario;
use App\Models\Llamadas;
use App\Models\TipoCaso;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\View\Components\Modal;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

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
                        Forms\Components\TextInput::make('medico_aph')
                            ->required()
                            ->readOnly()
                            ->default('Brandon Mejia')
                            ->placeholder('Médico APH de Turno')
                            ->label('Médico APH de turno')
                            ->prefixIcon('healthicons-o-doctor')
                            ->columnSpan(1),
                        Forms\Components\DateTimePicker::make('hora_creacion')
                            ->label('Hora de Creación')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar-date-range')
                            ->readOnly()
                            ->default(Carbon::now())->withoutSeconds()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('telefono_alertante')
                            ->required()
                            ->readOnly()
                            ->label('Telefono del alertante')
                            ->prefixIcon('heroicon-o-phone')
                            ->default('6146 8848')
                            ->placeholder('0000-0000')
                            ->columnSpan(1)
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
                        Forms\Components\Select::make('tipo_caso')
                            ->required()
                            ->prefixIcon('heroicon-o-archive-box')
                            ->options(TipoCaso::all()->pluck('name', 'name')) // Opciones como [id => name]
                            ->reactive() // Permite que las actualizaciones afecten otros campos
                            ->columnSpan(1),

                        Forms\Components\Select::make('opcion_pertinente')
                            ->required()
                            ->hidden(fn(callable $get) => $get('tipo_caso') != TipoCaso::where('name', 'Informativa')->value('name')) // Compara con el ID del tipo "Informativa"
                            ->options([
                                'Información Sanitaria' => 'Información Sanitaria',
                                'Teleorientación Médica' => 'Teleorientación Médica',
                                'Felicitaciones' => 'Felicitaciones',
                                'Reclamos' => 'Reclamos',
                                'No relacionadas a Salud' => 'No relacionadas a Salud',
                            ])
                            ->label('Opciones del Pertinente')
                            ->prefixIcon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('descripcion_caso')
                            ->required()
                            ->label('Descripción de Llamada')
                            ->placeholder('Ingrese la descripción de la llamada')
                            ->columnSpan(2),

                    ])->columns(3),
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
                            ->columnSpan(1),

                    ])->columns(3),
                Actions::make([
                    Actions\Action::make('crear_atencion')
                        ->color('danger')
                        ->icon('heroicon-o-plus')
                        ->action(function (array $data): void {
                            Llamadas::create([
                                'medico_aph' => $data['medico_aph'],
                                'cod_ambulancia' => $data['cod_ambulancia'],
                                'hora_creacion' => $data['hora_creacion'],
                                'telefono_alertante' => $data['telefono_alertante'],
                                'nombre_alertante' => $data['nombre_alertante'],
                                'motivo_literal' => $data['motivo_literal'],
                                'tipo_caso' => $data['tipo_caso'],
                                'descripcion_caso' => $data['descripcion_caso'],
                                'lugar_origen' => $data['lugar_origen'],
                                'lugar_destino' => $data['lugar_destino'],
                                'opcion_pertinente' => $data['opcion_pertinente'],
                            ]);
                            Notification::make()
                                ->title('Atención creada correctamente')
                                ->success()
                                ->icon('heroicon-o-check-circle')
                                ->date(Carbon::now())
                                ->persistent()
                                ->send();
                        })
                        ->modalHeading('Crear Atención')
                        ->modalIcon('heroicon-o-plus-circle')
                        ->modalDescription('La atención se creará automáticamente al cerrar la llamada. Si cancela, regresará al formulario de llamada.')
                        ->modalSubmitActionLabel('Sí, crear atención'),
                    Actions\Action::make('asociar_llamada')
                        ->label('Asociar Llamada')
                        ->icon('heroicon-o-link')
                        ->color('success')
                        ->form([
                            Select::make('caso_id')
                                ->label('Seleccionar Caso')
                                ->searchable()
                                ->prefixIcon('heroicon-o-archive-box')
                                ->options(Caso::query()->pluck('nu_caso', 'nu_caso')->toArray()) // Muestra "nu_caso" como opciones
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $caso = Caso::find($state);
                                    if ($caso) {
                                        $set('nu_caso', $caso->nu_caso);
                                        $set('telefono_alertante', $caso->telefono_alertante);
                                        $set('nombre_alertante', $caso->nombre_alertante);
                                        $set('motivo_literal', $caso->motivo_literal);
                                        $set('tipo_caso', $caso->tipo_caso);
                                        $set('descripcion_caso', $caso->descripcion_caso);
                                    }
                                })
                                ->columnSpan(1),
                            Fieldset::make('Datos de Ambulancia')
                                ->schema([
                                    TextInput::make('nu_caso')
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

            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('llamada_correlativo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('medico_aph')
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono_alertante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_alertante')
                    ->sortable(),
                Tables\Columns\TextColumn::make('motivo_literal')
                    ->limit(20)
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_caso')
                    ->badge()
                ->Color(function ($record) {
                    $tipo_caso = $record->tipo_caso;                   
                    
                    if ($tipo_caso === "Asistencia") {
                        return 'primary';
                    }
                    if ($tipo_caso === "Traslado") {
                        return 'danger';
                    }
                    if ($tipo_caso === "Evento") {
                        return 'amarillo';
                    }
                    if ($tipo_caso === "Autorización de Ambulancia a Préstamo") {
                        return 'verde';
                    }
                    if ($tipo_caso === "Informativa") {
                        return 'gray';
                    }
                
                }),
                Tables\Columns\TextColumn::make('hora_creacion')
                    ->dateTime()
                    ->sortable(),
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
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLlamadas::route('/'),
            //   'edit' => Pages\EditLlamadas::route('/{record}/edit'),
        ];
    }
}

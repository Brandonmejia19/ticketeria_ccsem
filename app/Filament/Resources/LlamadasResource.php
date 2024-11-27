<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LlamadasResource\Pages;
use App\Filament\Resources\LlamadasResource\RelationManagers;
use App\Models\Ambulancia;
use App\Models\CentroSanitario;
use App\Models\Llamadas;
use App\Models\TipoCaso;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
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
                Fieldset::make('Datos de Llamada')
                    ->schema([
                        Forms\Components\TextInput::make('atención_aph')
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
                            ->options(                                      
                                TipoCaso::all()->pluck('name', 'id'),
                            )
                            ->live()
                            ->lazy()
                            ->reactive() // Habilita que el formulario reaccione a los cambios en este campo
                            ->columnSpan(1),
                        Forms\Components\Select::make('opcion_pertinente')
                            ->required()
                            ->hidden(fn(callable $get) => $get('tipo_caso') !== 'Informativa') // Oculta si no es "Prestamo de Ambulancias"
                            ->required()
                            ->options([
                                'Información Sanitaria',
                                'Teleorientación Médica',
                                'Felicitaciones',
                                'Reclamos',
                                'No relacionadas a Salud',
                            ])
                            ->native()
                            ->label('Opciones del Pertinente')
                            ->lazy()
                            ->prefixIcon('heroicon-o-chat-bubble-oval-left-ellipsis')
                            ->live()
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('descripcion_caso')
                            ->required()
                            ->label('Descripción de Llamada')
                            ->placeholder('Ingrese la descripción de la llamada')
                            ->columnSpan(2),

                        Fieldset::make('Datos de Ambulancia')->hidden(fn(callable $get) => $get('tipo_caso') !== 2) // Oculta si no es "Prestamo de Ambulancias"
                            ->schema([
                                Forms\Components\Select::make('')                                 
                                    ->required()
                                    ->label('Lugar de Origen')
                                    ->lazy()
                                    ->live()
                                    ->prefixIcon('heroicon-o-home-modern')
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        CentroSanitario::all()->pluck('name', 'id'),
                                    )
                                    ->columnSpan(1),
                                Forms\Components\Select::make('lugar_destino')                                    
                                    ->required()
                                    ->label('Lugar de Destino')
                                    ->lazy()
                                    ->prefixIcon('heroicon-o-building-office-2')
                                    ->live()
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        CentroSanitario::all()->pluck('name', 'id'),
                                    )
                                    ->columnSpan(1),
                                Forms\Components\Select::make('cod_ambulancia')
                                    ->required()
                                    ->label('Codigo de Ambulancia')                                   
                                    ->prefixIcon('healthicons-o-ambulance')
                                    ->options(                                      //Si se colocan corchetes se lee como array y coloca todo junto
                                        Ambulancia::all()->pluck('unidad', 'id'),
                                    )
                                    ->columnSpan(1),

                            ])->columns(3),



                    ])->columns(3),
                Actions::make([
                    Actions\Action::make('crear_atencion')
                        ->label('Crear Atención')
                        ->color('danger')
                        ->icon('heroicon-o-plus')
                        ->action(fn() => $this->cerrarLlamada()),
                    Actions\Action::make('asociar_llamada')
                        ->label('Asociar Llamada')
                        ->icon('heroicon-o-link')
                        ->color('success')
                        ->form([
                            Select::make('llamada_id')
                                ->label('Seleccionar Llamada')
                                ->prefixIcon('heroicon-o-archive-box')
                                ->options(Llamadas::query()->pluck('hora_creacion', 'id')->toArray()) // Consulta eficiente
                                ->columnSpan(1),
                        ])
                        ->slideOver() // Usamos un formulario deslizante
                        ->action(function (array $data): void {
                            // Implementar la lógica para asociar la llamada
                            $llamadaId = $data['llamada_id'];

                            // Ejemplo de lógica: asociar llamada al recurso actual
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
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

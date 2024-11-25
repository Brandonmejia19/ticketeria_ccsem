<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LlamadasResource\Pages;
use App\Filament\Resources\LlamadasResource\RelationManagers;
use App\Models\Llamadas;
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

class LlamadasResource extends Resource
{
    protected static ?string $model = Llamadas::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Casos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Formulario de Llamada')
                    ->schema([
                        Fieldset::make('Datos de Llamada')
                            ->schema([
                                Forms\Components\TextInput::make('atención_aph')
                                    ->required()
                                    ->placeholder('Médico APH de Turno')
                                    ->label('Médico APH de turno')
                                    ->prefixIcon('healthicons-o-doctor')
                                    ->columnSpan(1),
                                Forms\Components\DateTimePicker::make('hora_creacion')
                                    ->required()
                                    ->prefixIcon('heroicon-o-calendar-date-range')
                                    ->readOnly()
                                    ->default(Carbon::now())
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('telefono_alertante')
                                    ->required()
                                    ->prefixIcon('heroicon-o-phone')
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
                                    ->options([
                                        'Informativa' => 'Informativa',
                                        'Asistencia Hospitalaria' => 'Asistencia Hospitalaria',
                                        'Prestamo de Ambulancias' => 'Prestamo de Ambulancias',
                                    ])
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
                                Fieldset::make('Datos Ambulancia a prestar')->hidden(fn(callable $get) => $get('tipo_caso') !== 'Prestamo de Ambulancias') // Oculta si no es "Prestamo de Ambulancias"
                                    ->schema([
                                        Forms\Components\Select::make('lugar_origen')
                                            ->hidden(fn(callable $get) => $get('tipo_caso') !== 'Prestamo de Ambulancias') // Oculta si no es "Prestamo de Ambulancias"
                                            ->required()
                                            ->label('Lugar de Origen')
                                            ->lazy()
                                            ->live()
                                            ->prefixIcon('heroicon-o-home-modern')

                                            ->options([
                                                'Hospital Central' => 'Hospital Central',
                                                'Clínica Local' => 'Clínica Local',
                                            ])
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('lugar_destino')
                                            ->hidden(fn(callable $get) => $get('tipo_caso') !== 'Prestamo de Ambulancias') // Oculta si no es "Prestamo de Ambulancias"
                                            ->required()
                                            ->label('Lugar de Destino')
                                            ->lazy()
                                            ->prefixIcon('heroicon-o-building-office-2')
                                            ->live()
                                            ->options([
                                                'Hospital Central' => 'Hospital Central',
                                                'Clínica Local' => 'Clínica Local',
                                            ])
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('cod_ambulancia')
                                            ->required()
                                            ->hidden(fn(callable $get) => $get('tipo_caso') !== 'Prestamo de Ambulancias') // Oculta si no es "Prestamo de Ambulancias"
                                            ->prefixIcon('healthicons-o-ambulance')
                                            ->columnSpan(1),
                                    ])->columns(3),

                            ])->columns(3),
                    ])->columns(3),

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
            'index' => Pages\ListLlamadas::route('/'),
            'create' => Pages\CreateLlamadas::route('/create'),
            'edit' => Pages\EditLlamadas::route('/{record}/edit'),
        ];
    }
}

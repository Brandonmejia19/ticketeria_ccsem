<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformativaResource\Pages;
use App\Filament\Resources\InformativaResource\RelationManagers;
use App\Models\Caso;
use App\Models\Informativa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;

class InformativaResource extends Resource
{
    protected static ?string $model = Caso::class;

    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: INFORMATIVA';
    protected static ?string $navigationLabel = 'Informativa';
  //  protected static ?string $navigationIcon = 'healthicons-o-crisis-response-center-person';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informativa')
                    ->schema([
                        Fieldset::make('INFORMACIÓN CASO')->schema([
                            Forms\Components\Select::make('tipo_caso')
                                ->prefixIcon('heroicon-o-folder-open')
                                ->label('Tipo de Caso')
                                ->default('Atencion PH')

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
                            Forms\Components\Textarea::make('notas_operador')
                                ->label('Notas')
                                ->placeholder('Notas dadas por el operador')
                                ->required()
                                ->columnSpan(5),
                            Forms\Components\Select::make('id')
                                ->searchable()
                                ->label('Asociar Caso')
                                ->options(Caso::all()->pluck('nu_caso', 'id'))
                                ->native(false)
                                ->columnSpan(1),
                        ])->columns(6),
                        Fieldset::make('Información Médico')->schema([
                            Forms\Components\Textarea::make('diagnostivo_presuntivo')
                                ->label('Diagnostico Presuntivo')
                                ->placeholder('Diagnostico Presuntivo')
                                ->required()
                                ->columnSpan(3)
                                ->maxLength(255),
                            Forms\Components\Select::make('id')
                                ->searchable()
                                ->label('Asociar Caso')
                                ->options(Caso::all()->pluck('nu_caso', 'id'))
                                ->native(false)
                                ->columnSpan(1),
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

                    ])->columnSpan(span: 3),
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
            'index' => Pages\ListInformativas::route('/'),
            'create' => Pages\CreateInformativa::route('/create'),
            'edit' => Pages\EditInformativa::route('/{record}/edit'),
        ];
    }
}

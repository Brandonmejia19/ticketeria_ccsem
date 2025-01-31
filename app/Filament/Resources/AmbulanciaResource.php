<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AmbulanciaResource\Pages;
use App\Filament\Resources\AmbulanciaResource\RelationManagers;
use App\Models\Ambulancia;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;

class AmbulanciaResource extends Resource
{
    protected static ?string $model = Ambulancia::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Desplegable';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ingreso de Ambulancia')
                    ->schema([
                        Forms\Components\TextInput::make('placa')
                            ->label('Placa de Ambulancia')
                            ->placeholder('N-000000')
                            ->required()
                            ->prefixIcon('heroicon-o-truck')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('unidad')
                            ->label('Unidad')
                            ->placeholder('Ingrese número de ambulancia')
                            ->required()
                            ->prefixIcon('heroicon-o-truck')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('kilometraje')
                            ->label('Kilometraje')
                            ->numeric()
                            ->suffix('km')
                            ->tel()
                            ->placeholder('Ingrese número de ambulancia')
                            ->required()
                            ->prefixIcon('mdi-car-speed-limiter')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('cyrus')
                            ->label('CYRUS')
                            ->placeholder('Ingrese el número asignado a CYRUS')
                            ->required()
                            ->numeric()
                            ->tel()
                            ->prefixIcon('heroicon-o-device-phone-mobile')
                            ->columnSpan(1),
                        Forms\Components\Select::make('estado_ambulancia')
                            ->label('Estado Actual de Ambulancia')
                            ->placeholder('Ingrese número de ambulancia')
                            ->required()
                            ->options(options: [
                                'Disponible' => 'Disponible',
                                'Mantenimiento' => 'Mantenimiento',
                                'No Disponible' => 'No Disponible',
                            ])
                            ->prefixIcon('heroicon-o-exclamation-circle')
                            ->columnSpan(1),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\ToggleButtons::make('camaras')
                                    ->label('Cámaras')
                                    ->options([
                                        'Si' => 'Si',
                                        'No' => 'No',
                                    ])
                                    ->required()
                                    ->inline()
                                    ->columnSpan(1),
                                Forms\Components\ToggleButtons::make('radio')
                                    ->label('Radio')
                                    ->required()
                                    ->options([
                                        'Si' => 'Si',
                                        'No' => 'No',
                                    ])
                                    ->inline()
                                    ->columnSpan(1),
                            ])->columnSpan(1),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('placa')
                    ->sortable()->placeholder('Sin datos'),
                Tables\Columns\TextColumn::make('unidad')
                    ->sortable()->placeholder('Sin datos'),
                Tables\Columns\TextColumn::make('kilometraje')
                    ->placeholder('Sin datos'),
                Tables\Columns\TextColumn::make('estado_ambulancia')
                    ->sortable()->placeholder('Sin datos'),
                Tables\Columns\TextColumn::make('camaras')
                    ->sortable()->placeholder('Sin datos'),
                Tables\Columns\TextColumn::make('radio')
                    ->sortable()->placeholder('Sin datos'),
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
            'index' => Pages\ListAmbulancias::route('/'),
            'create' => Pages\CreateAmbulancia::route('/create'),
            'edit' => Pages\EditAmbulancia::route('/{record}/edit'),
        ];
    }
}

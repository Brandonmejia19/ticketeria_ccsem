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
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidad')
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
            'index' => Pages\ListAmbulancias::route('/'),
            'create' => Pages\CreateAmbulancia::route('/create'),
            'edit' => Pages\EditAmbulancia::route('/{record}/edit'),
        ];
    }
}

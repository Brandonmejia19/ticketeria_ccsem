<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HerramientasambResource\Pages;
use App\Filament\Resources\HerramientasambResource\RelationManagers;
use App\Models\Herramientasamb;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class HerramientasambResource extends Resource
{
    protected static ?string $model = Herramientasamb::class;

    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $label = 'Herramientas de Ambulancias';
    protected static ?string $navigationLabel = 'Herramientas de Ambulancias';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->placeholder('Ingrese el nombre de la herramienta de ambulancia')
                            ->required()
                            ->prefixIcon('heroicon-o-wrench-screwdriver')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
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
            'index' => Pages\ListHerramientasambs::route('/'),
            'create' => Pages\CreateHerramientasamb::route('/create'),
            'edit' => Pages\EditHerramientasamb::route('/{record}/edit'),
        ];
    }
}

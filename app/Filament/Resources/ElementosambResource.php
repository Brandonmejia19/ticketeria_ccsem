<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ElementosambResource\Pages;
use App\Filament\Resources\ElementosambResource\RelationManagers;
use App\Models\Elementosamb;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class ElementosambResource extends Resource
{
    protected static ?string $model = Elementosamb::class;
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $label = 'Elementos de Ambulancias';
    protected static ?string $navigationLabel = 'Elementos de Ambulancias';
    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Section::make('Ingreso de Elementos de Ambulancias')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->prefixIcon('heroicon-o-rectangle-group')
                            ->placeholder('Ingrese el nombre de la herramienta de ambulancia')
                            ->columnSpanFull(),
                    ])
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
            'index' => Pages\ListElementosambs::route('/'),
            'create' => Pages\CreateElementosamb::route('/create'),
            'edit' => Pages\EditElementosamb::route('/{record}/edit'),
        ];
    }
}

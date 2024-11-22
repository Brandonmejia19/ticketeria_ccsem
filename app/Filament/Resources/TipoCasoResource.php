<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoCasoResource\Pages;
use App\Filament\Resources\TipoCasoResource\RelationManagers;
use App\Models\TipoCaso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipoCasoResource extends Resource
{
    protected static ?string $model = TipoCaso::class;

    protected static ?string $navigationIcon = 'healthicons-o-call-centre';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Tipo de Casos';
    protected static ?string $label = 'Tipo de Casos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ListTipoCasos::route('/'),
            'create' => Pages\CreateTipoCaso::route('/create'),
            'edit' => Pages\EditTipoCaso::route('/{record}/edit'),
        ];
    }
}

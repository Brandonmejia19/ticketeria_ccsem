<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResAtencionResource\Pages;
use App\Filament\Resources\ResAtencionResource\RelationManagers;
use App\Models\ResAtencion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResAtencionResource extends Resource
{
    protected static ?string $model = ResAtencion::class;

    protected static ?string $navigationIcon = 'healthicons-o-nerve';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Resolución de Atenciones';
    protected static ?string $label = 'Resolución de Atenciones';

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
            'index' => Pages\ListResAtencions::route('/'),
            'create' => Pages\CreateResAtencion::route('/create'),
            'edit' => Pages\EditResAtencion::route('/{record}/edit'),
        ];
    }
}

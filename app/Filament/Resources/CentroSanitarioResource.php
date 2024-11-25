<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CentroSanitarioResource\Pages;
use App\Filament\Resources\CentroSanitarioResource\RelationManagers;
use App\Models\CentroSanitario;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;


class CentroSanitarioResource extends Resource
{
    protected static ?string $model = CentroSanitario::class;

    protected static ?string $navigationIcon = 'healthicons-o-ambulatory-clinic';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Centro Sanitario';
    protected static ?string $label = 'Centro Sanitario';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Ingreso de Centro de Salud')
                    ->schema([
                        Forms\Components\Textinput::make('name')
                            ->label('Centro de Salud')
                            ->placeholder('Ingrese Centro de Salud')
                            ->required()
                            ->prefixIcon('healthicons-o-ambulatory-clinic')
                            ->columnSpan(1)
                    ])->columns(3)
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
            'index' => Pages\ListCentroSanitarios::route('/'),
            'create' => Pages\CreateCentroSanitario::route('/create'),
            'edit' => Pages\EditCentroSanitario::route('/{record}/edit'),
        ];
    }
}

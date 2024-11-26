<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrasladoNoefectivoResource\Pages;
use App\Filament\Resources\TrasladoNoefectivoResource\RelationManagers;
use App\Models\traslado_noefectivo;
use App\Models\TrasladoNoefectivo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Section as ComponentsSection;

class TrasladoNoefectivoResource extends Resource
{
    protected static ?string $model = traslado_noefectivo::class;

    protected static ?string $navigationIcon = 'healthicons-o-negative';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Razón de Traslado no efectivo';
    protected static ?string $label = 'Razones y traslados no efectivos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ingreso de Centro de Salud')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Razón de traslado')
                            ->placeholder('Ingrese Razón de traslado')
                            ->required()
                            ->columnSpan(1),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
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
            'index' => Pages\ListTrasladoNoefectivos::route('/'),
            'create' => Pages\CreateTrasladoNoefectivo::route('/create'),
            'edit' => Pages\EditTrasladoNoefectivo::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotivoExclusionResource\Pages;
use App\Filament\Resources\MotivoExclusionResource\RelationManagers;
use App\Models\MotivoExclusion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;

class MotivoExclusionResource extends Resource
{
    protected static ?string $model = MotivoExclusion::class;

    protected static ?string $navigationIcon = 'healthicons-o-insurance-card';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Exclusiones';
    protected static ?string $label = 'Motivos de Exclusiones';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Ingreso de Centro de Salud')
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Motivos de Exclusión')
                ->placeholder('Ingrese Motivo de Exclusion')
                ->required()
                ->prefixIcon('healthicons-o-insurance-card')
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
            'index' => Pages\ListMotivoExclusions::route('/'),
            'create' => Pages\CreateMotivoExclusion::route('/create'),
            'edit' => Pages\EditMotivoExclusion::route('/{record}/edit'),
        ];
    }
}

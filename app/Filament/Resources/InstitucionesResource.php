<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitucionesResource\Pages;
use App\Filament\Resources\InstitucionesResource\RelationManagers;
use App\Models\Instituciones;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class InstitucionesResource extends Resource
{
    protected static ?string $model = Instituciones::class;
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $label = 'Instituciones';
    protected static ?string $navigationLabel = 'Instituciones';
    protected static ?string $navigationIcon = 'healthicons-o-emergency-post';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Nombre'),
                    Forms\Components\TextInput::make('descripcion')
                        ->label('Descripcion'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('descripcion')
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
            'index' => Pages\ListInstituciones::route('/'),
            'create' => Pages\CreateInstituciones::route('/create'),
            'edit' => Pages\EditInstituciones::route('/{record}/edit'),
        ];
    }
}

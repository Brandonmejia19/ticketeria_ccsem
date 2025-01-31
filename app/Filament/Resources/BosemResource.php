<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BosemResource\Pages;
use App\Filament\Resources\BosemResource\RelationManagers;
use App\Models\Bosem;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BosemResource extends Resource
{
    protected static ?string $model = Bosem::class;
    protected static ?string $label = 'Bases Operativa';
    protected static ?string $navigationLabel = 'Bases Operativas';

    protected static ?string $navigationGroup = 'Flota';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('nombre')
                        ->prefixIcon('heroicon-o-document-text')
                        ->placeholder('Ingrese el nombre perteneciente a Base Operativa')
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('contacto')
                        ->placeholder('####-####')
                        ->prefixIcon('heroicon-o-phone')
                        ->tel(8)
                        ->columnSpan(1)
                        ->required()
                        ->numeric(),
                    Forms\Components\Textarea::make('direccion')
                        ->required()
                        ->placeholder('Ingrese la dirección perteneciente a Base Operativa')
                        ->columnSpanFull(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contacto')
                    ->numeric()
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
            'index' => Pages\ListBosems::route('/'),
            'create' => Pages\CreateBosem::route('/create'),
            'edit' => Pages\EditBosem::route('/{record}/edit'),
        ];
    }
}

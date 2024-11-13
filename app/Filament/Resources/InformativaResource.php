<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InformativaResource\Pages;
use App\Filament\Resources\InformativaResource\RelationManagers;
use App\Models\Caso;
use App\Models\Informativa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InformativaResource extends Resource
{
    protected static ?string $model = Caso::class;

    protected static ?string $navigationGroup = 'Casos';
    protected static ?string $label = ' CASO: INFORMATIVA';
    protected static ?string $navigationLabel = 'Informativa';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListInformativas::route('/'),
            'create' => Pages\CreateInformativa::route('/create'),
            'edit' => Pages\EditInformativa::route('/{record}/edit'),
        ];
    }
}

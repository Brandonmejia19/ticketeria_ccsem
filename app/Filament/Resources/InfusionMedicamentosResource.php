<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfusionMedicamentosResource\Pages;
use App\Filament\Resources\InfusionMedicamentosResource\RelationManagers;
use App\Models\infusion_medicamentos;
use App\Models\InfusionMedicamentos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class InfusionMedicamentosResource extends Resource
{
    protected static ?string $navigationIcon = 'healthicons-o-blood-bag';
    protected static ?string $navigationGroup = 'Desplegable';
    protected static ?string $navigationLabel = 'Medicamentos de Infusión';
    protected static ?string $label = 'Medicamentos de Infusión';
    protected static ?string $model = infusion_medicamentos::class;
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
            'index' => Pages\ListInfusionMedicamentos::route('/'),
            'create' => Pages\CreateInfusionMedicamentos::route('/create'),
            'edit' => Pages\EditInfusionMedicamentos::route('/{record}/edit'),
        ];
    }
}

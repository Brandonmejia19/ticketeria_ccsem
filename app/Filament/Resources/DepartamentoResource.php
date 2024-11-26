<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartamentoResource\Pages;
use App\Filament\Resources\DepartamentoResource\RelationManagers;
use App\Models\Departamento;
use App\Models\Distrito;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Forms\Components\TextInput;
use Filament\Resources\Tables\Components\TextColumn;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';
    protected static ?string $navigationGroup = 'Desplegable';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Igrese Nombre del Departamento:')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Departamento')
                            ->placeholder('Ingrese nombre del Departamento')
                            ->required()
                            ->prefixIcon('heroicon-o-globe-americas')
                            ->columnSpan(1),
                    ])->columns(3)


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
            'index' => Pages\ListDepartamentos::route('/'),
            'create' => Pages\CreateDepartamento::route('/create'),
            'edit' => Pages\EditDepartamento::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\CasoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Llamadas2RelationManager extends RelationManager
{
    protected static string $relationship = 'llamadas2';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('llamada_correlativa')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('llamada_correlativa')
            ->columns([
                Tables\Columns\TextColumn::make('llamada_correlativa'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
           //  Tables\Actions\AssociateAction::make()->preloadRecordSelect()->recordSelectSearchColumns('llamada_correlativa'),
             //   Tables\Actions\AttachAction::make(), // Permite asociar llamadas
              //  Tables\Actions\AssociateAction::make(), // Permite asociar llamadas

            ])
            ->actions([
                Tables\Actions\AssociateAction::make()
                    ->recordSelect(
                        fn(Forms\Components\Select $select) => $select
                            ->relationship('llamadas2', 'llamada_correlativo')
                            ->preload()
                            ->searchable()
                            ->multiple()
                            ->label('Llamadas Asociadas')
                            ->placeholder('Selecciona una o más llamadas'),
                    ),
                Tables\Actions\DetachAction::make(), // Acción para desasociar si es necesario.
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

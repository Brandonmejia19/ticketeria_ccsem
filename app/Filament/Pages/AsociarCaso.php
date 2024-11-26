<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Caso;
use Filament\Tables\Actions\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Components\ViewComponent;
class AsociarCaso extends Page implements \Filament\Tables\Contracts\HasTable
{
    use InteractsWithTable;
    protected static ?string $model = Caso::class;

    protected static string $view = 'filament.pages.asociar-caso';
    public function table(Table $table): Table
    {
        return $table
        ->query(Caso::query())
            ->columns([
                Tables\Columns\TextColumn::make('tipo_caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nu_caso')
                    ->searchable(),

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
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListaChequeoResource\Pages;
use App\Filament\Resources\ListaChequeoResource\RelationManagers;
use App\Models\ListaChequeo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class ListaChequeoResource extends Resource
{
    protected static ?string $model = ListaChequeo::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Listas de Chequeo';
    protected static ?string $label = 'Listas de Chequeo';
    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Section::make()
                    ->schema([
                        Forms\Components\Select::make('ambulancia_di')
                        ->required()
                        ->options(Ambulancias::class->pluck()->all()),
                        Forms\Components\DatePicker::make('fecha')
                            ->required(),
                        Forms\Components\Textarea::make('turno')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('AEM')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('nivel_combustible')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('cupones')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('cantidad_cupones')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('entrega_factura')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('cantidad_factura')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('numeros_factura')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('detalles_daños')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('aem_entrega')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('entrega_firma')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('aem_recibe')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('recibe_firma')
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('observaciones')
                            ->required()
                            ->columnSpan(1),
                    ])->columns(2)
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
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
            'index' => Pages\ListListaChequeos::route('/'),
            'create' => Pages\CreateListaChequeo::route('/create'),
            'edit' => Pages\EditListaChequeo::route('/{record}/edit'),
        ];
    }
}

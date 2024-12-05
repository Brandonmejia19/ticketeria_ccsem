<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstadoCasoResource\Pages;
use App\Filament\Resources\EstadoCasoResource\RelationManagers;
use App\Models\EstadoCaso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstadoCasoResource extends Resource
{
    protected static ?string $navigationGroup = 'Desplegable';

    protected static ?string $model = EstadoCaso::class;
    protected static ?string $label = 'Estado de Ambulancias: Registro';
    protected static ?string $navigationLabel = 'Estado de Ambulancias: Registro';
    protected static ?string $navigationIcon = 'healthicons-o-mobile-clinic';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('AR')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_ar'),
                Forms\Components\TextInput::make('E')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_e'),
                Forms\Components\TextInput::make('EL')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_el'),
                Forms\Components\TextInput::make('EA')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_ea'),
                Forms\Components\TextInput::make('EC')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_ec'),
                Forms\Components\TextInput::make('EE')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_ee'),
                Forms\Components\TextInput::make('ED')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_ed'),
                Forms\Components\TextInput::make('D')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_d'),
                Forms\Components\Textarea::make('notas')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('nu_caso')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cod_ambu')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('AR')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ar')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('E')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_e')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_el')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EA')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ea')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EC')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ec')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EE')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ee')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ED')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_ed')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('D')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_d')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nu_caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_ambu')
                    ->searchable(),
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
            'index' => Pages\ListEstadoCasos::route('/'),
            'create' => Pages\CreateEstadoCaso::route('/create'),
            'edit' => Pages\EditEstadoCaso::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Filament\Resources\ImaginationResource\Pages;
use App\Filament\Resources\ImaginationResource\RelationManagers;
use App\Models\Imagination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImaginationResource extends Resource
{
    protected static ?string $model = Imagination::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'API';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('element')
                    ->options(Element::class)
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('element')
                    ->translateLabel()
                    ->sortable()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImaginations::route('/'),
            'create' => Pages\CreateImagination::route('/create'),
            'edit' => Pages\EditImagination::route('/{record}/edit'),
            'view' => Pages\ViewImagination::route('/{record}'),
        ];
    }
}

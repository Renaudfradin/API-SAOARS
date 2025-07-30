<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Filament\Resources\ImaginationResource\Pages;
use App\Models\Imagination;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImaginationResource extends Resource
{
    protected static ?string $model = Imagination::class;

    protected static ?string $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Imaginations');
    }

    public static function getModelLabel(): string
    {
        return __('Imagination');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Imaginations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Nom'))
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->translateLabel()
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('description')
                    ->label(__('Description'))
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('element')
                    ->label(__('Element'))
                    ->options(Element::class)
                    ->native(false)
                    ->required(),

                Forms\Components\TextInput::make('character')
                    ->label(__('Character'))
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('image')
                    ->label(__('Image'))
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('element')
                    ->label(__('Element'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('character')
                    ->label(__('Character'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('element')
                    ->options(Element::class)
                    ->searchable()
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbilityResource\Pages;
use App\Models\Ability;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AbilityResource extends Resource
{
    protected static ?string $model = Ability::class;

    protected static ?string $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Abilités');
    }

    public static function getModelLabel(): string
    {
        return __('Abilité');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Abilités');
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

                Forms\Components\TextInput::make('descripton')
                    ->label(__('Description'))
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('type')
                    ->label(__('Type'))
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('start')
                    ->label(__('Début'))
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbilities::route('/'),
            'create' => Pages\CreateAbility::route('/create'),
            'edit' => Pages\EditAbility::route('/{record}/edit'),
            'view' => Pages\ViewAbility::route('/{record}'),
        ];
    }
}

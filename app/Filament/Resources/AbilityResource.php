<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbilityResource\Pages\CreateAbility;
use App\Filament\Resources\AbilityResource\Pages\EditAbility;
use App\Filament\Resources\AbilityResource\Pages\ListAbilities;
use App\Filament\Resources\AbilityResource\Pages\ViewAbility;
use App\Models\Ability;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AbilityResource extends Resource
{
    protected static ?string $model = Ability::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Nom'))
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label(__('Slug'))
                    ->translateLabel()
                    ->maxLength(255)
                    ->required(),

                TextInput::make('descripton')
                    ->label(__('Description'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('type')
                    ->label(__('Type'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('start')
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
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListAbilities::route('/'),
            'create' => CreateAbility::route('/create'),
            'edit' => EditAbility::route('/{record}/edit'),
            'view' => ViewAbility::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Enums\AttackType;
use App\Filament\Resources\AttackResource\Pages\CreateAttack;
use App\Filament\Resources\AttackResource\Pages\EditAttack;
use App\Filament\Resources\AttackResource\Pages\ListAttacks;
use App\Filament\Resources\AttackResource\Pages\ViewAttack;
use App\Models\Attack;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AttackResource extends Resource
{
    protected static ?string $model = Attack::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Attaques');
    }

    public static function getModelLabel(): string
    {
        return __('Attaque');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Attaques');
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

                Textarea::make('description')
                    ->label(__('Description'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('mp_cost')
                    ->label(__('Coût en MP'))
                    ->numeric()
                    ->required(),

                Select::make('type_atk')
                    ->label(__('Type d\'attaque'))
                    ->options(AttackType::class)
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

                TextColumn::make('mp_cost')
                    ->label(__('Coût en MP'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type_atk')
                    ->label(__('Type d\'attaque'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type_atk')
                    ->options(AttackType::class)
                    ->searchable()
                    ->native(false),

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

    public static function getPages(): array
    {
        return [
            'index' => ListAttacks::route('/'),
            'create' => CreateAttack::route('/create'),
            'edit' => EditAttack::route('/{record}/edit'),
            'view' => ViewAttack::route('/{record}'),
        ];
    }
}

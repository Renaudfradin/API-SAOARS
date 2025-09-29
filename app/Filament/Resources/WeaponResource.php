<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Filament\Resources\WeaponResource\Pages\CreateWeapon;
use App\Filament\Resources\WeaponResource\Pages\EditWeapon;
use App\Filament\Resources\WeaponResource\Pages\ListWeapons;
use App\Filament\Resources\WeaponResource\Pages\ViewWeapon;
use App\Models\Weapon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WeaponResource extends Resource
{
    protected static ?string $model = Weapon::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('Armes');
    }

    public static function getModelLabel(): string
    {
        return __('Arme');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Armes');
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

                Select::make('type')
                    ->label(__('Type'))
                    ->options(WeaponType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Select::make('element_weapons')
                    ->label(__('Element'))
                    ->options(Element::class)
                    ->native(false)
                    ->required(),

                Section::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('hp')
                            ->label(__('HP'))
                            ->numeric()
                            ->required(),

                        TextInput::make('mp')
                            ->label(__('MP'))
                            ->numeric()
                            ->required(),

                        TextInput::make('atk')
                            ->label(__('ATK'))
                            ->numeric()
                            ->required(),

                        TextInput::make('matk')
                            ->label(__('MATK'))
                            ->numeric()
                            ->required(),

                        TextInput::make('def')
                            ->label(__('DEF'))
                            ->numeric()
                            ->required(),

                        TextInput::make('mdef')
                            ->label(__('MDEF'))
                            ->numeric()
                            ->required(),

                        TextInput::make('crit')
                            ->label(__('CRIT'))
                            ->numeric()
                            ->required(),

                        TextInput::make('spd')
                            ->label(__('SPD'))
                            ->numeric()
                            ->required(),

                        TextInput::make('start')
                            ->label(__('Start'))
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                TextInput::make('effect_1')
                    ->label(__('Effect 1'))
                    ->required(),

                TextInput::make('effect_2')
                    ->label(__('Effect 2')),

                TextInput::make('effect_3')
                    ->label(__('Effect 3')),

                Select::make('characters_id')
                    ->label(__('Character main'))
                    ->relationship('character', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload(),

                FileUpload::make('image')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('weapon')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->required(),

                FileUpload::make('image2')
                    ->label(__('Image 2'))
                    ->disk('scaleway')
                    ->directory('weapon')
                    ->image()
                    ->downloadable()
                    ->openable()
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
                    ->sortable()
                    ->searchable(),

                TextColumn::make('element_weapons')
                    ->label(__('Element'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start')
                    ->label(__('Start'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('element_weapons')
                    ->options(Element::class)
                    ->searchable()
                    ->native(false),

                SelectFilter::make('type')
                    ->options(WeaponType::class)
                    ->searchable()
                    ->native(false),

                SelectFilter::make('start')
                    ->options([
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ]),
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
            'index' => ListWeapons::route('/'),
            'create' => CreateWeapon::route('/create'),
            'edit' => EditWeapon::route('/{record}/edit'),
            'view' => ViewWeapon::route('/{record}'),
        ];
    }
}

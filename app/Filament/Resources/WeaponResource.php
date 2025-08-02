<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Filament\Resources\WeaponResource\Pages;
use App\Models\Weapon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class WeaponResource extends Resource
{
    protected static ?string $model = Weapon::class;

    protected static ?string $navigationGroup = 'Contenu';

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

                Forms\Components\Select::make('type')
                    ->label(__('Type'))
                    ->options(WeaponType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('element_weapons')
                    ->label(__('Element'))
                    ->options(Element::class)
                    ->native(false)
                    ->required(),

                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('hp')
                            ->label(__('HP'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('mp')
                            ->label(__('MP'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('atk')
                            ->label(__('ATK'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('matk')
                            ->label(__('MATK'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('def')
                            ->label(__('DEF'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('mdef')
                            ->label(__('MDEF'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('crit')
                            ->label(__('CRIT'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('spd')
                            ->label(__('SPD'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('start')
                            ->label(__('Start'))
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('effect_1')
                    ->label(__('Effect 1'))
                    ->required(),

                Forms\Components\TextInput::make('effect_2')
                    ->label(__('Effect 2')),

                Forms\Components\TextInput::make('effect_3')
                    ->label(__('Effect 3')),

                Forms\Components\Select::make('characters_id')
                    ->label(__('Character main'))
                    ->relationship('character', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload(),

                Forms\Components\FileUpload::make('image')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('weapon')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->required(),

                Forms\Components\FileUpload::make('image2')
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nom'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('element_weapons')
                    ->label(__('Element'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start')
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
            'index' => Pages\ListWeapons::route('/'),
            'create' => Pages\CreateWeapon::route('/create'),
            'edit' => Pages\EditWeapon::route('/{record}/edit'),
            'view' => Pages\ViewWeapon::route('/{record}'),
        ];
    }
}

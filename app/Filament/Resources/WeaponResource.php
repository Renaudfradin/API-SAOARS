<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Filament\Resources\WeaponResource\Pages;
use App\Models\Weapon;
use Filament\Forms;
use Filament\Forms\Components\Section;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'API';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->translateLabel()
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options(WeaponType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('element_weapons')
                    ->options(Element::class)
                    ->native(false)
                    ->required(),

                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('hp')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('mp')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('atk')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('matk')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('def')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('mdef')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('crit')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('spd')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('start')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('effect_1')
                    ->required(),

                Forms\Components\TextInput::make('effect_2'),

                Forms\Components\TextInput::make('effect_3'),

                Forms\Components\Select::make('characters_id')
                    ->label('Character main')
                    ->relationship('character', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload(),

                Forms\Components\FileUpload::make('image')
                    ->disk('scaleway')
                    ->directory('weapon')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->columnSpan('full')
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

                Tables\Columns\TextColumn::make('element_weapons')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start')
                    ->translateLabel()
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

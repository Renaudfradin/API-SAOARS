<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Filament\Resources\CharacterResource\Pages;
use App\Models\Character;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $navigationGroup = 'Contenu';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('Personnages');
    }

    public static function getModelLabel(): string
    {
        return __('Personnage');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Personnages');
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

                Forms\Components\Select::make('special_partner')
                    ->label('Partenaire')
                    ->relationship('specialPartner', 'name')
                    ->native(false),

                Forms\Components\Textarea::make('profile')
                    ->label(__('Profil'))
                    ->autosize()
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Select::make('element')
                    ->label(__('Element'))
                    ->options(Element::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('atk1')
                    ->label(__('Attaque 1'))
                    ->relationship('attack', 'name')
                    ->native(false)
                    ->required(),

                Forms\Components\Select::make('atk2')
                    ->label(__('Attaque 2'))
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\Select::make('atk3')
                    ->label(__('Attaque 3'))
                    ->relationship('attack', 'name')
                    ->native(false),

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
                            ->label(__('Atk'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('matk')
                            ->label(__('Matk'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('def')
                            ->label(__('Def'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('mdef')
                            ->label(__('Mdef'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('crit')
                            ->label(__('Crit'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('spd')
                            ->label(__('Spd'))
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('start')
                            ->label(__('Start'))
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                Forms\Components\TextInput::make('ultime')
                    ->label(__('Ultime'))
                    ->required(),

                Forms\Components\TextInput::make('ultime_description')
                    ->label(__('Ultime description'))
                    ->required(),

                Forms\Components\TextInput::make('enhance')
                    ->label(__('Enhance')),

                Forms\Components\Select::make('enhance_atk')
                    ->label(__('Enhance atk'))
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\Select::make('enhance_atk2')
                    ->label(__('Enhance atk2'))
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\TextInput::make('cost')
                    ->label(__('Cost'))
                    ->numeric()
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('character')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->required(),

                Forms\Components\FileUpload::make('image2')
                    ->label(__('Image 2'))
                    ->disk('scaleway')
                    ->directory('character')
                    ->image()
                    ->downloadable()
                    ->openable()
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

                Tables\Columns\TextColumn::make('banner_id')
                    ->label(__('Banner'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('element')
                    ->label(__('Element'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start')
                    ->label(__('Start'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('element')
                    ->options(Element::class)
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
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
            'view' => Pages\ViewCharacter::route('/{record}'),
        ];
    }
}

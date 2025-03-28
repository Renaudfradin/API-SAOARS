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
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

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

                Forms\Components\TextInput::make('profile')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->autosize()
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Select::make('element')
                    ->options(Element::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('atk1')
                    ->label('Atk 1')
                    ->relationship('attack', 'name')
                    ->native(false)
                    ->required(),

                Forms\Components\Select::make('atk2')
                    ->label('Atk 2')
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\Select::make('atk3')
                    ->label('Atk 3')
                    ->relationship('attack', 'name')
                    ->native(false),

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

                Forms\Components\TextInput::make('ultime')
                    ->required(),

                Forms\Components\TextInput::make('ultime_description')
                    ->required(),

                Forms\Components\TextInput::make('enhance'),

                Forms\Components\Select::make('enhance_atk')
                    ->label('enhance_atk')
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\Select::make('enhance_atk2')
                    ->label('enhance_atk2')
                    ->relationship('attack', 'name')
                    ->native(false),

                Forms\Components\TextInput::make('start')
                    ->numeric()
                    ->default(1)
                    ->required(),

                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('special_partner')
                    ->label('specialPartner')
                    ->relationship('specialPartner', 'name')
                    ->native(false),

                Forms\Components\FileUpload::make('image')
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
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('banner_id')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start')
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
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}/edit'),
        ];
    }
}

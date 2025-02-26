<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Filament\Resources\WeaponResource\Pages;
use App\Filament\Resources\WeaponResource\RelationManagers;
use App\Models\Weapon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WeaponResource extends Resource
{
    protected static ?string $model = Weapon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'API';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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

                Forms\Components\TextInput::make('effect_1')
                    ->required(),

                Forms\Components\TextInput::make('effect_2'),

                Forms\Components\TextInput::make('effect_3'),

                Forms\Components\TextInput::make('characters_id')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('start')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListWeapons::route('/'),
            'create' => Pages\CreateWeapon::route('/create'),
            'edit' => Pages\EditWeapon::route('/{record}/edit'),
        ];
    }
}

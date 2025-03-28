<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\EquipmentType;
use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'API';

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
                    ->options(EquipmentType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('type_equipment')
                    ->options(Element::class)
                    ->native(false)
                    ->searchable()
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

                Forms\Components\TextInput::make('effect_1'),

                Forms\Components\TextInput::make('effect_2'),

                Forms\Components\FileUpload::make('image')
                    ->disk('scaleway')
                    ->directory('equipment')
                    ->image()
                    ->downloadable()
                    ->openable()
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
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type_equipment')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('start')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(EquipmentType::class)
                    ->native(false),

                SelectFilter::make('type_equipment')
                    ->options(Element::class)
                    ->searchable()
                    ->native(false),

                SelectFilter::make('start')
                    ->options([
                        '2' => '2',
                        '3' => '3',
                        '4' => '4'
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
            'view' => Pages\ViewEquipment::route('/{record}'),
        ];
    }
}

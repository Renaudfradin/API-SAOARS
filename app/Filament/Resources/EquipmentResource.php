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

    protected static ?string $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Equipements');
    }

    public static function getModelLabel(): string
    {
        return __('Equipement');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Equipements');
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
                    ->options(EquipmentType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('type_equipment')
                    ->label(__('Type d\'equipement'))
                    ->options(Element::class)
                    ->native(false)
                    ->searchable()
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

                Forms\Components\TextInput::make('effect_1')
                    ->label(__('Effet 1')),

                Forms\Components\TextInput::make('effect_2')
                    ->label(__('Effet 2')),

                Forms\Components\FileUpload::make('image')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('equipment')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->required(),

                Forms\Components\FileUpload::make('image2')
                    ->label(__('Image 2'))
                    ->disk('scaleway')
                    ->directory('equipment')
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

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type_equipment')
                    ->label(__('Type d\'equipement'))
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
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
            'view' => Pages\ViewEquipment::route('/{record}'),
        ];
    }
}

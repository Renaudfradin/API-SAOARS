<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Enums\EquipmentType;
use App\Filament\Resources\EquipmentResource\Pages\CreateEquipment;
use App\Filament\Resources\EquipmentResource\Pages\EditEquipment;
use App\Filament\Resources\EquipmentResource\Pages\ListEquipment;
use App\Filament\Resources\EquipmentResource\Pages\ViewEquipment;
use App\Models\Equipment;
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

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

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
                    ->options(EquipmentType::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Select::make('type_equipment')
                    ->label(__('Type d\'equipement'))
                    ->options(Element::class)
                    ->native(false)
                    ->searchable()
                    ->required(),

                Section::make()
                    ->columns(3)
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
                            ->label(__('Atk'))
                            ->numeric()
                            ->required(),

                        TextInput::make('matk')
                            ->label(__('Matk'))
                            ->numeric()
                            ->required(),

                        TextInput::make('def')
                            ->label(__('Def'))
                            ->numeric()
                            ->required(),

                        TextInput::make('mdef')
                            ->label(__('Mdef'))
                            ->numeric()
                            ->required(),

                        TextInput::make('crit')
                            ->label(__('Crit'))
                            ->numeric()
                            ->required(),

                        TextInput::make('spd')
                            ->label(__('Spd'))
                            ->numeric()
                            ->required(),

                        TextInput::make('start')
                            ->label(__('Start'))
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ]),

                TextInput::make('effect_1')
                    ->label(__('Effet 1')),

                TextInput::make('effect_2')
                    ->label(__('Effet 2')),

                FileUpload::make('image')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('equipment')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->required(),

                FileUpload::make('image2')
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

                TextColumn::make('type_equipment')
                    ->label(__('Type d\'equipement'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('start')
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
            'index' => ListEquipment::route('/'),
            'create' => CreateEquipment::route('/create'),
            'edit' => EditEquipment::route('/{record}/edit'),
            'view' => ViewEquipment::route('/{record}'),
        ];
    }
}

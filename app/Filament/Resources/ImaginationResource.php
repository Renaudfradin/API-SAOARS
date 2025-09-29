<?php

namespace App\Filament\Resources;

use App\Enums\Element;
use App\Filament\Resources\ImaginationResource\Pages\CreateImagination;
use App\Filament\Resources\ImaginationResource\Pages\EditImagination;
use App\Filament\Resources\ImaginationResource\Pages\ListImaginations;
use App\Filament\Resources\ImaginationResource\Pages\ViewImagination;
use App\Models\Imagination;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ImaginationResource extends Resource
{
    protected static ?string $model = Imagination::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Imaginations');
    }

    public static function getModelLabel(): string
    {
        return __('Imagination');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Imaginations');
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

                TextInput::make('description')
                    ->label(__('Description'))
                    ->maxLength(255)
                    ->required(),

                Select::make('element')
                    ->label(__('Element'))
                    ->options(Element::class)
                    ->native(false)
                    ->required(),

                TextInput::make('character')
                    ->label(__('Character'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('image')
                    ->label(__('Image'))
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('element')
                    ->label(__('Element'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('character')
                    ->label(__('Character'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('element')
                    ->options(Element::class)
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
            'index' => ListImaginations::route('/'),
            'create' => CreateImagination::route('/create'),
            'edit' => EditImagination::route('/{record}/edit'),
            'view' => ViewImagination::route('/{record}'),
        ];
    }
}

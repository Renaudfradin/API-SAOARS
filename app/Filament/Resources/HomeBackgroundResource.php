<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeBackgroundResource\Pages\CreateHomeBackground;
use App\Filament\Resources\HomeBackgroundResource\Pages\EditHomeBackground;
use App\Filament\Resources\HomeBackgroundResource\Pages\ListHomeBackgrounds;
use App\Filament\Resources\HomeBackgroundResource\Pages\ViewHomeBackground;
use App\Filament\Schemas\CatalogTranslation;
use App\Models\HomeBackground;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeBackgroundResource extends Resource
{
    protected static ?string $model = HomeBackground::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('Fonds d\'accueil');
    }

    public static function getModelLabel(): string
    {
        return __('Fond d\'accueil');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Fonds d\'accueil');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                CatalogTranslation::notice(),

                TextInput::make('name')
                    ->label(__('Nom'))
                    ->maxLength(255)
                    ->required(),

                Select::make('world')
                    ->label(__('Monde'))
                    ->options(HomeBackground::WORLDS)
                    ->native(false),

                TextInput::make('rarity')
                    ->label(__('Rareté'))
                    ->numeric(),

                TextInput::make('fanadata_id')
                    ->label(__('FanaData'))
                    ->numeric()
                    ->required(),

                FileUpload::make('image')
                    ->label(__('Miniature'))
                    ->disk('scaleway')
                    ->directory('galleries/home/backgrounds/thumbnail')
                    ->image()
                    ->downloadable()
                    ->openable(),

                FileUpload::make('image_large')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('galleries/home/backgrounds')
                    ->image()
                    ->downloadable()
                    ->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Miniature'))
                    ->disk('scaleway')
                    ->checkFileExistence(false),

                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->searchable(),

                TextColumn::make('world')
                    ->label(__('Monde'))
                    ->formatStateUsing(fn ($state): ?string => HomeBackground::WORLDS[(int) $state] ?? null),

                TextColumn::make('rarity')
                    ->label(__('Rareté'))
                    ->sortable(),

                TextColumn::make('fanadata_id')
                    ->label(__('FanaData'))
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->iconButton()
                    ->color('gray'),
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
            'index' => ListHomeBackgrounds::route('/'),
            'create' => CreateHomeBackground::route('/create'),
            'edit' => EditHomeBackground::route('/{record}/edit'),
            'view' => ViewHomeBackground::route('/{record}'),
        ];
    }
}

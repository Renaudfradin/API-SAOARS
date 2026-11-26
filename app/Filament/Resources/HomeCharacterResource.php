<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeCharacterResource\Pages\CreateHomeCharacter;
use App\Filament\Resources\HomeCharacterResource\Pages\EditHomeCharacter;
use App\Filament\Resources\HomeCharacterResource\Pages\ListHomeCharacters;
use App\Filament\Resources\HomeCharacterResource\Pages\ViewHomeCharacter;
use App\Filament\Schemas\CatalogTranslation;
use App\Models\HomeCharacter;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomeCharacterResource extends Resource
{
    protected static ?string $model = HomeCharacter::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getNavigationLabel(): string
    {
        return __('Personnages d\'accueil');
    }

    public static function getModelLabel(): string
    {
        return __('Personnage d\'accueil');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Personnages d\'accueil');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                CatalogTranslation::notice(),

                TextInput::make('person_name')
                    ->label(__('Personne'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('title')
                    ->label(__('Titre'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('person_key')
                    ->label(__('Clé'))
                    ->maxLength(255)
                    ->required(),

                TextInput::make('type')
                    ->label(__('Type'))
                    ->numeric(),

                TextInput::make('fanadata_id')
                    ->label(__('FanaData'))
                    ->numeric()
                    ->required(),

                FileUpload::make('image')
                    ->label(__('Miniature'))
                    ->disk('scaleway')
                    ->directory('galleries/home/characters/thumbnail')
                    ->image()
                    ->downloadable()
                    ->openable(),

                FileUpload::make('image_large')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('galleries/home/characters')
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

                TextColumn::make('person_name')
                    ->label(__('Personne'))
                    ->translateLabel()
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('Titre'))
                    ->translateLabel()
                    ->searchable(),

                TextColumn::make('person_key')
                    ->label(__('Clé'))
                    ->searchable(),

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
            'index' => ListHomeCharacters::route('/'),
            'create' => CreateHomeCharacter::route('/create'),
            'edit' => EditHomeCharacter::route('/{record}/edit'),
            'view' => ViewHomeCharacter::route('/{record}'),
        ];
    }
}

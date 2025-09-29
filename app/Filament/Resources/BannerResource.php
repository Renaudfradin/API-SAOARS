<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages\CreateBanner;
use App\Filament\Resources\BannerResource\Pages\EditBanner;
use App\Filament\Resources\BannerResource\Pages\ListBanners;
use App\Filament\Resources\BannerResource\Pages\ViewBanner;
use App\Models\Banner;
use App\Models\Character;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BannerResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $model = Banner::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenu';

    public static function getNavigationLabel(): string
    {
        return __('Bannières');
    }

    public static function getModelLabel(): string
    {
        return __('Bannière');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bannières');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->translateLabel()
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

                DatePicker::make('from')
                    ->label(__('De'))
                    ->required(),

                DatePicker::make('to')
                    ->label(__('Au'))
                    ->required(),

                Select::make('characters')
                    ->label(__('Personnages'))
                    ->multiple()
                    ->options(Character::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('img')
                    ->label(__('Image'))
                    ->disk('scaleway')
                    ->directory('banner')
                    ->image()
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull()
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

                TextColumn::make('from')
                    ->label(__('De'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('to')
                    ->label(__('Au'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
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
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
            'view' => ViewBanner::route('/{record}'),
        ];
    }
}

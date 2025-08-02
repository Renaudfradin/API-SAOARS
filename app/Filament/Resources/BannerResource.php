<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use App\Models\Character;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BannerResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $model = Banner::class;

    protected static ?string $navigationGroup = 'Contenu';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
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

                Forms\Components\DatePicker::make('from')
                    ->label(__('De'))
                    ->required(),

                Forms\Components\DatePicker::make('to')
                    ->label(__('Au'))
                    ->required(),

                Forms\Components\Select::make('characters')
                    ->label(__('Personnages'))
                    ->multiple()
                    ->options(Character::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\FileUpload::make('img')
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
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Nom'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('from')
                    ->label(__('De'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('to')
                    ->label(__('Au'))
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
            'view' => Pages\ViewBanner::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages\CreateBanner;
use App\Filament\Resources\BannerResource\Pages\EditBanner;
use App\Filament\Resources\BannerResource\Pages\ListBanners;
use App\Filament\Resources\BannerResource\Pages\ViewBanner;
use App\Filament\Schemas\CatalogTranslation;
use App\Models\Banner;
use App\Models\Character;
use Filament\Actions\ActionGroup;
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
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                CatalogTranslation::notice(),
                TextInput::make('name')
                    ->translateLabel()
                    ->label(__('Nom'))
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(CatalogTranslation::slugFromFrenchName()),

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

                Select::make('character_ids')
                    ->label(__('Personnages'))
                    ->multiple()
                    ->relationship('characters', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' - '.$record->description)
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->columnSpanFull(),

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
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('to')
                    ->label(__('Au'))
                    ->translateLabel()
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('date')
                    ->label('Date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Date')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['date'] ?? null,
                            fn (Builder $query, string $date): Builder => $query
                                ->whereDate('from', '<=', $date)
                                ->whereDate('to', '>=', $date),
                        );
                    })
                    ->indicateUsing(function (array $state): array {
                        if (blank($state['date'] ?? null)) {
                            return [];
                        }

                        return [Indicator::make('Date : '.$state['date'])];
                    }),

                SelectFilter::make('character')
                    ->label('Personnage')
                    ->options(fn (): array => Character::query()
                        ->get()
                        ->sortBy(fn (Character $character): string => (string) $character->name)
                        ->mapWithKeys(fn (Character $character): array => [$character->id => (string) $character->name])
                        ->all())
                    ->searchable()
                    ->native(false)
                    ->query(function (Builder $query, array $data): Builder {
                        $id = $data['value'] ?? null;

                        if (blank($id)) {
                            return $query;
                        }

                        $id = (int) $id;

                        return $query->where(function (Builder $query) use ($id): void {
                            $query->whereJsonContains('character_ids', $id)
                                ->orWhereJsonContains('character_ids', (string) $id)
                                ->orWhereHas('characters', fn (Builder $query): Builder => $query->whereKey($id));
                        });
                    }),
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
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
            'view' => ViewBanner::route('/{record}'),
        ];
    }
}

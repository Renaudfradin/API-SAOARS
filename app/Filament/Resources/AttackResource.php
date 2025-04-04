<?php

namespace App\Filament\Resources;

use App\Enums\AttackType;
use App\Filament\Resources\AttackResource\Pages;
use App\Models\Attack;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AttackResource extends Resource
{
    protected static ?string $model = Attack::class;

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

                Forms\Components\Textarea::make('description')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('mp_cost')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('type_atk')
                    ->options(AttackType::class)
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

                Tables\Columns\TextColumn::make('description')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('mp_cost')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type_atk')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('type_atk')
                    ->options(AttackType::class)
                    ->searchable()
                    ->native(false),
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
            'index' => Pages\ListAttacks::route('/'),
            'create' => Pages\CreateAttack::route('/create'),
            'edit' => Pages\EditAttack::route('/{record}/edit'),
            'view' => Pages\ViewAttack::route('/{record}'),
        ];
    }
}

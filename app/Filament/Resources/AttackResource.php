<?php

namespace App\Filament\Resources;

use App\Enums\AttackType;
use App\Filament\Resources\AttackResource\Pages;
use App\Filament\Resources\AttackResource\RelationManagers;
use App\Models\Attack;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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

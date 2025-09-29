<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'email';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('Utilisateurs');
    }

    public static function getModelLabel(): string
    {
        return __('Utilisateur');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Utilisateurs');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Nom'))
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label(__('Email'))
                    ->required()
                    ->email()
                    ->maxLength(255),

                Select::make('role')
                    ->label(__('Role'))
                    ->options(Role::class)
                    ->native(false)
                    ->required(),

                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn (string $state) => Hash::make($state))
                    ->dehydrated(fn (string $state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->confirmed()
                    ->live()
                    ->minLength(6),

                TextInput::make('password_confirmation')
                    ->label(__('Password confirmation'))
                    ->password()
                    ->revealable()
                    ->minLength(6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Nom'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('role')
                    ->label(__('Role'))
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}

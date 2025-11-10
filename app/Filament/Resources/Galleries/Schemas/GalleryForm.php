<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Enums\GalleryType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('type')
                    ->options(GalleryType::class)
                    ->native(false)
                    ->required(),

                FileUpload::make('image')
                    ->image()
                    ->disk('scaleway')
                    ->directory('galleries')
                    ->required(),
            ]);
    }
}

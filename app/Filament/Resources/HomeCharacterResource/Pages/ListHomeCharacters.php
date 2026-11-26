<?php

namespace App\Filament\Resources\HomeCharacterResource\Pages;

use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\HomeCharacterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeCharacters extends ListRecords
{
    use SelectsCatalogLocale;

    protected static string $resource = HomeCharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

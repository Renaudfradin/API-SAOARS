<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\CharacterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCharacter extends CreateRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
        ];
    }
}

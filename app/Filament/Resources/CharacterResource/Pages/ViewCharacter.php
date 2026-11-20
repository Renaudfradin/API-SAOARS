<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\CharacterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCharacter extends ViewRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}

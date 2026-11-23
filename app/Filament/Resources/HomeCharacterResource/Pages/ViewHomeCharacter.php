<?php

namespace App\Filament\Resources\HomeCharacterResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\HomeCharacterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHomeCharacter extends ViewRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = HomeCharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}

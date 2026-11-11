<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\AbilityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbility extends CreateRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
        ];
    }
}

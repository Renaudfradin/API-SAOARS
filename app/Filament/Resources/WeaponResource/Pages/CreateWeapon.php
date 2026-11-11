<?php

namespace App\Filament\Resources\WeaponResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\WeaponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWeapon extends CreateRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = WeaponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\WeaponResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Concerns\TranslatesCatalogRecord;
use App\Filament\Resources\WeaponResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWeapon extends EditRecord
{
    use SelectsCatalogLocale;
    use TranslatesCatalogRecord {
        TranslatesCatalogRecord::fillForm insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordCreation insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordUpdate insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::updatedActiveLocale insteadof SelectsCatalogLocale;
    }

    protected static string $resource = WeaponResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            $this->translateCatalogAction(),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

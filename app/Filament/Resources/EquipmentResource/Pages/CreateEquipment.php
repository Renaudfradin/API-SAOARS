<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Concerns\TranslatesCatalogRecord;
use App\Filament\Resources\EquipmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEquipment extends CreateRecord
{
    use SelectsCatalogLocale;
    use TranslatesCatalogRecord {
        TranslatesCatalogRecord::fillForm insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordCreation insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordUpdate insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::updatedActiveLocale insteadof SelectsCatalogLocale;
    }

    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            $this->translateCatalogAction(),
        ];
    }
}

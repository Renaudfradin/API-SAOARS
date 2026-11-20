<?php

namespace App\Filament\Resources\AttackResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Concerns\TranslatesCatalogRecord;
use App\Filament\Resources\AttackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttack extends CreateRecord
{
    use SelectsCatalogLocale;
    use TranslatesCatalogRecord {
        TranslatesCatalogRecord::fillForm insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordCreation insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::handleRecordUpdate insteadof SelectsCatalogLocale;
        TranslatesCatalogRecord::updatedActiveLocale insteadof SelectsCatalogLocale;
    }

    protected static string $resource = AttackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            $this->translateCatalogAction(),
        ];
    }
}

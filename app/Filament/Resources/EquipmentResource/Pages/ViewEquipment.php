<?php

namespace App\Filament\Resources\EquipmentResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\EquipmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipment extends ViewRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = EquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}

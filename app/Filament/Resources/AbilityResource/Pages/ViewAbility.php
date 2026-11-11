<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\AbilityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAbility extends ViewRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}

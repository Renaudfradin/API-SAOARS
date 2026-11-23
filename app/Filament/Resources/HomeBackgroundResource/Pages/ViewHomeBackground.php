<?php

namespace App\Filament\Resources\HomeBackgroundResource\Pages;

use App\Filament\Actions\CatalogLocaleSwitcher;
use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Resources\HomeBackgroundResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHomeBackground extends ViewRecord
{
    use SelectsCatalogLocale;

    protected static string $resource = HomeBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CatalogLocaleSwitcher::make(),
            EditAction::make(),
        ];
    }
}

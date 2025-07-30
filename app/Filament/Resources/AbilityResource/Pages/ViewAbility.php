<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Resources\AbilityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAbility extends ViewRecord
{
    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

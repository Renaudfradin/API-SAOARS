<?php

namespace App\Filament\Resources\AbilityResource\Pages;

use App\Filament\Resources\AbilityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAbility extends EditRecord
{
    protected static string $resource = AbilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

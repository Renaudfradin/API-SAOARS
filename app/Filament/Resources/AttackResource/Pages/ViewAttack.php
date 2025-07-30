<?php

namespace App\Filament\Resources\AttackResource\Pages;

use App\Filament\Resources\AttackResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAttack extends ViewRecord
{
    protected static string $resource = AttackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

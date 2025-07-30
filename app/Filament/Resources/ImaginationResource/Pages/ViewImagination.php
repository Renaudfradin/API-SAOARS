<?php

namespace App\Filament\Resources\ImaginationResource\Pages;

use App\Filament\Resources\ImaginationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewImagination extends ViewRecord
{
    protected static string $resource = ImaginationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

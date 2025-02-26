<?php

namespace App\Filament\Resources\ImaginationResource\Pages;

use App\Filament\Resources\ImaginationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImagination extends EditRecord
{
    protected static string $resource = ImaginationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

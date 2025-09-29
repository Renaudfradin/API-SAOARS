<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

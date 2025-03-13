<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Imports\BannerImporter;
use App\Filament\Resources\BannerResource;
use App\Jobs\ImportCsv;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(BannerImporter::class)
                ->job(ImportCsv::class),

            Actions\CreateAction::make(),
        ];
    }
}

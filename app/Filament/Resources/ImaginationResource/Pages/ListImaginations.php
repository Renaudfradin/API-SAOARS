<?php

namespace App\Filament\Resources\ImaginationResource\Pages;

use App\Filament\Concerns\SelectsCatalogLocale;
use App\Filament\Imports\ImaginationImporter;
use App\Filament\Resources\ImaginationResource;
use App\Jobs\ImportCsv;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListImaginations extends ListRecords
{
    use SelectsCatalogLocale;

    protected static string $resource = ImaginationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ImaginationImporter::class)
                ->job(ImportCsv::class),

            CreateAction::make(),
        ];
    }
}

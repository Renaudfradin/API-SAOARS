<?php

namespace App\Filament\Resources\ImaginationResource\Pages;

use App\Filament\Imports\ImaginationImporter;
use App\Filament\Resources\ImaginationResource;
use App\Jobs\ImportCsv;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListImaginations extends ListRecords
{
    protected static string $resource = ImaginationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ImaginationImporter::class)
                ->job(ImportCsv::class),

            Actions\CreateAction::make(),
        ];
    }
}

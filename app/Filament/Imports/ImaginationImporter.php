<?php

namespace App\Filament\Imports;

use App\Models\Imagination;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ImaginationImporter extends Importer
{
    protected static ?string $model = Imagination::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('element')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Imagination
    {
        return new Imagination;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your imagination import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}

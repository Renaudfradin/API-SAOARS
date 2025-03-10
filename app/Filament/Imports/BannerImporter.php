<?php

namespace App\Filament\Imports;

use App\Models\Banner;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BannerImporter extends Importer
{
    protected static ?string $model = Banner::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('from')
                ->requiredMapping()
                ->rules(['required', 'datetime']),
            ImportColumn::make('to')
                ->requiredMapping()
                ->rules(['required', 'datetime']),
            ImportColumn::make('characters')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('img'),
        ];
    }

    public function resolveRecord(): ?Banner
    {
        // return Banner::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Banner();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your banner import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}

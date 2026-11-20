<?php

namespace App\Filament\Actions;

use App\Support\Locales;
use Filament\Actions\SelectAction;

class CatalogLocaleSwitcher extends SelectAction
{
    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Langue');
        $this->options(Locales::LABELS);
    }
}

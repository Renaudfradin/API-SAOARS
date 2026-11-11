<?php

namespace App\Filament\Concerns;

use App\Filament\Translatable\SpatieTranslatableContentDriver;
use App\Support\Locales;
use Filament\Support\Contracts\TranslatableContentDriver;

trait SelectsCatalogLocale
{
    public ?string $activeLocale = Locales::DEFAULT;

    public function getActiveSchemaLocale(): ?string
    {
        return $this->activeLocale;
    }

    public function getActiveTableLocale(): ?string
    {
        return $this->activeLocale;
    }

    /**
     * @return class-string<TranslatableContentDriver>|null
     */
    public function getFilamentTranslatableContentDriver(): ?string
    {
        return SpatieTranslatableContentDriver::class;
    }
}

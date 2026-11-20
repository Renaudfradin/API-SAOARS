<?php

namespace App\Filament\Schemas;

use App\Support\Locales;
use Closure;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class CatalogTranslation
{
    public static function notice(): Placeholder
    {
        return Placeholder::make('translation_progress')
            ->hiddenLabel()
            ->content('Le service choisi traduit la fiche à partir du français. Reste sur cette page : le résultat s’affichera ici.')
            ->visible(fn (mixed $livewire): bool => is_object($livewire) && filled($livewire->translationAgentId ?? null))
            ->columnSpanFull();
    }

    public static function slugFromFrenchName(): Closure
    {
        return function (Set $set, ?string $state, mixed $livewire): void {
            $locale = is_object($livewire) ? ($livewire->activeLocale ?? null) : null;

            if (is_string($locale) && $locale !== Locales::DEFAULT) {
                return;
            }

            $set('slug', Str::slug((string) $state));
        };
    }
}

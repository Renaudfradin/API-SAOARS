<?php

namespace App\Http\Resources\Concerns;

use App\Support\Locales;

trait PresentsTranslations
{
    protected function requestedLocale(): string
    {
        return Locales::resolve(request()->query('lang'));
    }

    protected function text(string $attribute): string
    {
        return $this->resource->translatedAttribute($attribute, $this->requestedLocale());
    }

    /**
     * @return array{lang: string, available_locales: list<string>}
     */
    protected function translationMeta(): array
    {
        return [
            'lang' => $this->resource->translationLocale($this->requestedLocale()),
            'available_locales' => $this->resource->availableLocales(),
        ];
    }

    /**
     * @return list<array{stage: int, hp: int, mp: int, atk: int, matk: int, def: int, mdef: int, crit: int, spd: int}>
     */
    protected function statPayload(): array
    {
        if (! method_exists($this->resource, 'statStages')) {
            return [];
        }

        return $this->resource->statStages
            ->map(fn ($stage): array => [
                'stage' => $stage->stage,
                'hp' => $stage->hp,
                'mp' => $stage->mp,
                'atk' => $stage->atk,
                'matk' => $stage->matk,
                'def' => $stage->def,
                'mdef' => $stage->mdef,
                'crit' => $stage->crit,
                'spd' => $stage->spd,
            ])
            ->values()
            ->all();
    }
}

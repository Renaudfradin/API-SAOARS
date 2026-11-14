<?php

namespace App\Models\Concerns;

use App\Support\Locales;
use Spatie\Translatable\HasTranslations;

trait HasCatalogTranslations
{
    use HasTranslations;

    public function getFallbackLocale(): string
    {
        return Locales::DEFAULT;
    }

    public function translationLocale(string $requested): string
    {
        $requested = Locales::resolve($requested);

        if ($requested !== Locales::DEFAULT && ! $this->hasLocaleContent($requested)) {
            return Locales::DEFAULT;
        }

        return $requested;
    }

    public function hasLocaleContent(string $locale): bool
    {
        foreach ($this->getTranslatableAttributes() as $attribute) {
            if (filled($this->getTranslation($attribute, $locale, false))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    public function availableLocales(): array
    {
        return array_values(array_filter(
            Locales::ALL,
            fn (string $locale): bool => $this->hasLocaleContent($locale),
        ));
    }

    public function translatedAttribute(string $attribute, string $requested): string
    {
        $locale = Locales::resolve($requested);
        $value = $this->getTranslation($attribute, $locale, false);

        if (is_string($value) && $value !== '') {
            return $value;
        }

        foreach (Locales::ALL as $candidate) {
            $fallback = $this->getTranslation($attribute, $candidate, false);

            if (is_string($fallback) && $fallback !== '') {
                return $fallback;
            }
        }

        return '';
    }

    /**
     * @param  array<string, string|null>  $translations
     */
    public function fillTranslations(string $attribute, array $translations, bool $keepExistingFrench = false): void
    {
        foreach (Locales::ALL as $locale) {
            $value = $translations[$locale] ?? null;

            if (! is_string($value) || $value === '') {
                continue;
            }

            if ($locale === Locales::DEFAULT && $keepExistingFrench && filled($this->getTranslation($attribute, Locales::DEFAULT, false))) {
                continue;
            }

            $this->setTranslation($attribute, $locale, $value);
        }
    }
}

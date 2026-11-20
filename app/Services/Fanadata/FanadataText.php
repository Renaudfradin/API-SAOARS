<?php

namespace App\Services\Fanadata;

use App\Support\Locales;
use Illuminate\Support\Str;

class FanadataText
{
    /**
     * @param  array<string, mixed>  $row
     * @return array<string, string>
     */
    public static function translations(array $row, string $prefix, bool $keepLines = false): array
    {
        $translations = [];

        foreach (Locales::FANADATA_SUFFIX as $locale => $suffix) {
            $value = self::clean($row[$prefix.'_'.$suffix] ?? null, $keepLines);

            if ($value !== null) {
                $translations[$locale] = $value;
            }
        }

        return $translations;
    }

    public static function clean(mixed $value, bool $keepLines = false): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = html_entity_decode($value);
        $value = strip_tags($value);
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = $keepLines
            ? (string) preg_replace("/[ \t]+/", ' ', $value)
            : (string) preg_replace('/\s+/u', ' ', $value);
        $value = trim($value);

        if ($value === '' || $value === '??') {
            return null;
        }

        return $value;
    }

    public static function key(?string $value): string
    {
        return Str::of(self::clean($value) ?? '')
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->toString();
    }
}

<?php

namespace App\Support;

class Locales
{
    public const DEFAULT = 'fr';

    /**
     * @var list<string>
     */
    public const ALL = ['fr', 'en', 'zh', 'ko', 'ja', 'de', 'es'];

    /**
     * @var array<string, string>
     */
    public const LABELS = [
        'fr' => 'Français',
        'en' => 'English',
        'zh' => '中文',
        'ko' => '한국어',
        'ja' => '日本語',
        'de' => 'Deutsch',
        'es' => 'Español',
    ];

    /**
     * FanaData uses Traditional Chinese under the `tw` suffix.
     *
     * @var array<string, string>
     */
    public const FANADATA_SUFFIX = [
        'fr' => 'fr',
        'en' => 'en',
        'zh' => 'tw',
        'ko' => 'ko',
        'ja' => 'ja',
        'de' => 'de',
        'es' => 'es',
    ];

    public static function resolve(mixed $locale): string
    {
        return is_string($locale) && in_array($locale, self::ALL, true)
            ? $locale
            : self::DEFAULT;
    }
}

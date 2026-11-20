<?php

namespace App\Services\Translation;

use RuntimeException;

class TranslationReply
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function decode(string $reply): array
    {
        $decoded = self::decodeJson($reply);
        $translations = [];

        foreach (TranslationPrompt::targets() as $locale) {
            $localeData = $decoded[$locale] ?? null;

            if (! is_array($localeData)) {
                throw new RuntimeException('La réponse ne contient pas la langue '.$locale.'.');
            }

            $fields = [];

            foreach ($localeData as $key => $value) {
                if (! is_string($key)) {
                    continue;
                }

                $fields[$key] = is_string($value) ? $value : '';
            }

            $translations[$locale] = $fields;
        }

        return $translations;
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeJson(string $reply): array
    {
        $reply = trim($reply);

        if (preg_match('/```(?:json)?\s*(.*?)```/s', $reply, $matches) === 1) {
            $reply = trim($matches[1]);
        }

        $decoded = json_decode($reply, true);

        if (! is_array($decoded)) {
            $start = strpos($reply, '{');
            $end = strrpos($reply, '}');

            if ($start !== false && $end !== false && $end >= $start) {
                $decoded = json_decode(substr($reply, $start, $end - $start + 1), true);
            }
        }

        if (! is_array($decoded)) {
            throw new RuntimeException('La réponse n’est pas un JSON exploitable.');
        }

        return $decoded;
    }
}

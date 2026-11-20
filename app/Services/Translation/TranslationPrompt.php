<?php

namespace App\Services\Translation;

use App\Support\Locales;

class TranslationPrompt
{
    /**
     * @param  array<string, string>  $french
     */
    public static function text(array $french): string
    {
        $targets = self::targets();
        $languages = [];

        foreach ($targets as $locale) {
            $languages[] = $locale.' ('.Locales::LABELS[$locale].')';
        }

        $fields = json_encode($french, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $languageList = implode(', ', $languages);
        $localeList = implode(', ', $targets);
        $fieldList = implode(', ', array_keys($french));

        return <<<PROMPT
        Tu traduis une fiche du jeu Sword Art Online Unleash Blading, du français vers plusieurs langues.
        Réponds uniquement avec un objet JSON, sans markdown et sans commentaire.
        Les clés de premier niveau sont exactement : {$localeList}.
        Chaque valeur reprend exactement les mêmes clés, dans le même ordre : {$fieldList}.
        Traduis les textes. Laisse vides les champs déjà vides. Conserve les nombres, les balises et les noms propres lorsqu’ils n’ont pas d’équivalent établi.
        Langues cibles : {$languageList}.
        N’utilise aucun outil et ne modifie aucun fichier.

        Fiche française :
        {$fields}
        PROMPT;
    }

    /**
     * @return list<string>
     */
    public static function targets(): array
    {
        return array_values(array_filter(
            Locales::ALL,
            fn (string $locale): bool => $locale !== Locales::DEFAULT,
        ));
    }
}

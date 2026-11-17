<?php

namespace App\Services\Translation;

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\Weapon;
use App\Support\Locales;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class EmptyCatalogTranslator
{
    /**
     * @var array<class-string<Model>, string>
     */
    public const MODELS = [
        Ability::class => 'Abilité',
        Attack::class => 'Attaque',
        Banner::class => 'Bannière',
        Character::class => 'Personnage',
        Equipment::class => 'Equipement',
        Imagination::class => 'Imagination',
        Weapon::class => 'Arme',
    ];

    public function __construct(
        private CatalogTranslationManager $manager,
        private CatalogContentTranslator $translator,
    ) {}

    /**
     * @return list<class-string<Model>>
     */
    public function models(?string $only = null): array
    {
        if ($only === null || trim($only) === '') {
            return array_keys(self::MODELS);
        }

        $needle = strtolower(trim($only));

        foreach (self::MODELS as $class => $label) {
            if (strtolower(class_basename($class)) === $needle || strtolower($label) === $needle) {
                return [$class];
            }
        }

        $known = implode(', ', array_map(class_basename(...), array_keys(self::MODELS)));

        throw new InvalidArgumentException('Modèle inconnu « '.$only.' ». Modèles disponibles : '.$known.'.');
    }

    /**
     * @param  class-string<Model>  $class
     * @return iterable<int, Model>
     */
    public function records(string $class): iterable
    {
        return $class::query()->orderBy('id')->cursor();
    }

    /**
     * @return array<string, string>
     */
    public function pendingFrench(Model $record): array
    {
        if (! method_exists($record, 'getTranslatableAttributes') || ! method_exists($record, 'getTranslation')) {
            return [];
        }

        $french = [];

        foreach ($record->getTranslatableAttributes() as $attribute) {
            $source = $this->text($record->getTranslation($attribute, Locales::DEFAULT, false));

            if ($source === '') {
                continue;
            }

            foreach (TranslationPrompt::targets() as $locale) {
                if ($this->text($record->getTranslation($attribute, $locale, false)) === '') {
                    $french[$attribute] = $source;

                    break;
                }
            }
        }

        return $french;
    }

    /**
     * @param  array<string, string>  $french
     */
    public function fill(Model $record, array $french): int
    {
        if ($french === [] || ! method_exists($record, 'getTranslation') || ! method_exists($record, 'setTranslation')) {
            return 0;
        }

        $translations = $this->translator->apply(
            $french,
            $this->manager->driver('cursor')->translate($french),
        );
        $written = 0;

        foreach (TranslationPrompt::targets() as $locale) {
            $fields = $translations[$locale] ?? null;

            if (! is_array($fields)) {
                continue;
            }

            foreach (array_keys($french) as $attribute) {
                if ($this->text($record->getTranslation($attribute, $locale, false)) !== '') {
                    continue;
                }

                $value = $fields[$attribute] ?? '';

                if (! is_string($value) || $this->text($value) === '') {
                    continue;
                }

                $record->setTranslation($attribute, $locale, $value);
                $written++;
            }
        }

        if ($written > 0) {
            $record->save();
        }

        return $written;
    }

    public function label(Model $record): string
    {
        $type = self::MODELS[$record::class] ?? class_basename($record);
        $name = method_exists($record, 'getTranslation')
            ? $this->text($record->getTranslation('name', Locales::DEFAULT, false))
            : '';
        $title = $name !== '' ? $name : '#'.$record->getKey();

        return $type.' '.$title;
    }

    private function text(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}

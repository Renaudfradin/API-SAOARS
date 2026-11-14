<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Character;
use App\Support\Locales;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaponDetailResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        $character = $this->characters_id ? Character::find($this->characters_id) : null;

        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'type' => $this->type instanceof \BackedEnum ? $this->type->value : $this->type,
            'element_weapons' => $this->element_weapons instanceof \BackedEnum ? $this->element_weapons->value : $this->element_weapons,
            'start' => $this->start,
            'fanadata_id' => $this->fanadata_id,
            'hp' => $this->hp,
            'mp' => $this->mp,
            'atk' => $this->atk,
            'matk' => $this->matk,
            'def' => $this->def,
            'mdef' => $this->mdef,
            'crit' => $this->crit,
            'spd' => $this->spd,
            'stats' => $this->statPayload(),
            'effect_1' => $this->text('effect_1'),
            'effect_2' => $this->text('effect_2'),
            'effect_3' => $this->text('effect_3'),
            'skills' => $this->skillPayload(),
            'locales' => $this->localeLines(),
            'characters_id' => $character ? CharacterResource::make($character)->resolve() : null,
            'image' => Media::url($this->image),
            'image2' => Media::url($this->image2),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }

    /**
     * @return array<string, array{name: string}>
     */
    private function localeLines(): array
    {
        $names = $this->resource->getTranslations('name');
        $lines = [];

        foreach (Locales::ALL as $locale) {
            $name = is_string($names[$locale] ?? null) ? $names[$locale] : '';

            if ($name === '') {
                continue;
            }

            $lines[$locale] = ['name' => $name];
        }

        return $lines;
    }

    /**
     * @return list<array{slot: int, name: string, lines: list<array{lb: int, text: string}>}>
     */
    private function skillPayload(): array
    {
        $groups = is_array($this->skills) ? $this->skills : [];

        if ($groups === []) {
            return $this->fallbackSkills();
        }

        $skills = [];

        foreach ($groups as $group) {
            if (! is_array($group)) {
                continue;
            }

            $lines = [];

            foreach ($group['lines'] ?? [] as $line) {
                if (! is_array($line)) {
                    continue;
                }

                $text = $this->translatedMap($line['text'] ?? []);

                if ($text === '') {
                    continue;
                }

                $lines[] = [
                    'lb' => (int) ($line['lb'] ?? 0),
                    'text' => $text,
                ];
            }

            if ($lines === []) {
                continue;
            }

            $first = $group['lines'][0] ?? [];
            $skills[] = [
                'slot' => (int) ($group['slot'] ?? count($skills) + 1),
                'name' => $this->translatedMap(is_array($first) ? ($first['name'] ?? []) : []),
                'lines' => $lines,
            ];
        }

        return $skills;
    }

    /**
     * @return list<array{slot: int, name: string, lines: list<array{lb: int, text: string}>}>
     */
    private function fallbackSkills(): array
    {
        $skills = [];

        foreach ([1 => 'effect_1', 2 => 'effect_2', 3 => 'effect_3'] as $slot => $attribute) {
            $text = $this->text($attribute);

            if ($text === '') {
                continue;
            }

            $skills[] = [
                'slot' => $slot,
                'name' => '',
                'lines' => [['lb' => 0, 'text' => $text]],
            ];
        }

        return $skills;
    }

    private function translatedMap(mixed $map): string
    {
        if (! is_array($map)) {
            return '';
        }

        foreach ([$this->requestedLocale(), 'en', 'fr'] as $locale) {
            $value = $map[$locale] ?? null;

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        foreach ($map as $value) {
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return '';
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Attack;
use App\Models\Character;
use App\Support\Locales;
use App\Support\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterDetailResource extends JsonResource
{
    use PresentsTranslations;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->text('name'),
            'slug' => $this->slug,
            'profile' => $this->text('profile'),
            'description' => $this->text('description'),
            'element' => $this->element instanceof \BackedEnum ? $this->element->value : $this->element,
            'weapon_type' => $this->weapon_type instanceof \BackedEnum ? $this->weapon_type->value : $this->weapon_type,
            'weapon' => $this->weaponCard(),
            'banner' => $this->bannerCard(),
            'locales' => $this->localeLines(),
            'atk1' => $this->attack($this->atk1),
            'atk2' => $this->attack($this->atk2),
            'atk3' => $this->attack($this->atk3),
            'hp' => $this->hp,
            'mp' => $this->mp,
            'atk' => $this->atk,
            'matk' => $this->matk,
            'def' => $this->def,
            'mdef' => $this->mdef,
            'crit' => $this->crit,
            'spd' => $this->spd,
            'stats' => $this->statPayload(),
            'ultime' => $this->text('ultime'),
            'ultime_description' => $this->text('ultime_description'),
            'enhance' => $this->text('enhance'),
            'enhance_atk' => $this->attack($this->enhance_atk),
            'enhance_atk2' => $this->attack($this->enhance_atk2),
            'start' => $this->start,
            'cost' => $this->cost,
            'special_partner' => $this->resolvePartner($this->special_partner),
            'image' => Media::url($this->image),
            'image2' => Media::url($this->image2),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function weaponCard(): ?array
    {
        $weapon = $this->weapon;

        if (! $weapon) {
            return null;
        }

        return [
            'id' => $weapon->id,
            'name' => $weapon->translatedAttribute('name', $this->requestedLocale()),
            'slug' => $weapon->slug,
            'type' => $weapon->type instanceof \BackedEnum ? $weapon->type->value : $weapon->type,
            'element' => $weapon->element_weapons instanceof \BackedEnum ? $weapon->element_weapons->value : $weapon->element_weapons,
            'start' => $weapon->start,
            'image' => Media::url($weapon->image2 ?: $weapon->image),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function bannerCard(): ?array
    {
        $banner = $this->banner;

        if (! $banner) {
            return null;
        }

        return [
            'id' => $banner->id,
            'name' => $banner->translatedAttribute('name', $this->requestedLocale()),
            'slug' => $banner->slug,
            'image' => Media::url($banner->img),
        ];
    }

    /**
     * @return array<string, array{name: string, description: string}>
     */
    private function localeLines(): array
    {
        $names = $this->resource->getTranslations('name');
        $titles = $this->resource->getTranslations('description');
        $lines = [];

        foreach (Locales::ALL as $locale) {
            $name = is_string($names[$locale] ?? null) ? $names[$locale] : '';
            $title = is_string($titles[$locale] ?? null) ? $titles[$locale] : '';

            if ($name === '' && $title === '') {
                continue;
            }

            $lines[$locale] = ['name' => $name, 'description' => $title];
        }

        return $lines;
    }

    private function attack(mixed $attackId): mixed
    {
        if (! $attackId) {
            return null;
        }

        $attack = Attack::find($attackId);

        return $attack ? AttackResource::make($attack)->resolve() : null;
    }

    /**
     * special_partner is sometimes a character id, sometimes a free-text name from imports.
     *
     * @return array<string, mixed>|null
     */
    private function resolvePartner(mixed $partner): ?array
    {
        if (blank($partner)) {
            return null;
        }

        $character = null;

        if (is_numeric($partner)) {
            $character = Character::find((int) $partner);
        } else {
            $name = (string) $partner;
            $character = Character::query()
                ->where('slug', $name)
                ->orWhere('name->fr', $name)
                ->orWhere('name->en', $name)
                ->first();
        }

        if ($character) {
            return CharacterResource::make($character)->resolve();
        }

        return [
            'id' => null,
            'name' => (string) $partner,
            'slug' => null,
            'image' => null,
        ];
    }
}

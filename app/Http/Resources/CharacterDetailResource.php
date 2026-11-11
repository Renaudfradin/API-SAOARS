<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\PresentsTranslations;
use App\Models\Attack;
use App\Models\Character;
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
            'element' => $this->element,
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
            'special_partner' => $this->special_partner ? Character::find($this->special_partner) : null,
            'image' => Media::url($this->image),
            'image2' => Media::url($this->image2),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            ...$this->translationMeta(),
        ];
    }

    private function attack(mixed $attackId): ?Attack
    {
        if (! $attackId) {
            return null;
        }

        return Attack::find($attackId);
    }
}

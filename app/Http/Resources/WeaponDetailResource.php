<?php

namespace App\Http\Resources;

use App\Models\Character;
use Illuminate\Http\Resources\Json\JsonResource;

class WeaponDetailResource extends JsonResource
{
    public function getCharacter($characterId)
    {
        return Character::find($characterId);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'element_weapons' => $this->element_weapons,
            'hp' => $this->hp,
            'mp' => $this->mp,
            'atk' => $this->atk,
            'matk' => $this->matk,
            'def' => $this->def,
            'mdef' => $this->mdef,
            'crit' => $this->crit,
            'spd' => $this->spd,
            'effect_1' => $this->effect_1,
            'effect_2' => $this->effect_2,
            'effect_3' => $this->effect_3,
            'characters_id' => $this->getCharacter($this->characters_id),
            'start' => $this->start,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

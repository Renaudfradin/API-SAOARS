<?php

namespace App\Http\Resources;

use App\Models\Attack;
use App\Models\Character;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterDetailResource extends JsonResource
{
    public function getAttack($attackId)
    {
        return Attack::find($attackId);
    }

    public function getSpecialPartner($characterId)
    {
        return Character::find($characterId);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'profile' => $this->profile,
            'description' => $this->description,
            'element' => $this->element,
            'atk1' => $this->getAttack($this->atk1),
            'atk2' => $this->getAttack($this->atk2),
            'atk3' => $this->getAttack($this->atk3),
            'hp' => $this->hp,
            'mp' => $this->mp,
            'atk' => $this->atk,
            'matk' => $this->matk,
            'def' => $this->def,
            'mdef' => $this->mdef,
            'crit' => $this->crit,
            'spd' => $this->spd,
            'ultime' => $this->ultime,
            'ultime_description' => $this->ultime_description,
            'enhance' => $this->enhance,
            'enhance_atk' => $this->getAttack($this->enhance_atk),
            'enhance_atk2' => $this->getAttack($this->enhance_atk2),
            'start' => $this->start,
            'cost' => $this->cost,
            'special_partner' => $this->getSpecialPartner($this->special_partner),
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

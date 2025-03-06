<?php

namespace App\Http\Resources;

use App\Models\Attack;
use Illuminate\Http\Resources\Json\JsonResource;
class CharacterResource extends JsonResource
{
    public function getAttack($attackId)
    {
        return Attack::find($attackId);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'profile' => $this->profile,
            'element' => $this->element,
            'atk1' => $this->getAttack($this->atk1)->type_atk,
            'atk2' => $this->getAttack($this->atk2)->type_atk,
            'atk3' => $this->getAttack($this->atk3)->type_atk,
            'start' => $this->start,
            'image' => $this->image,
            'created_at' => $this->created_at,
        ];
    }
}

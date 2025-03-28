<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EquipmentDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'type_equipment' => $this->type_equipment,
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
           'image' => Storage::disk('scaleway')->url($this->image),
            'start' => $this->start,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

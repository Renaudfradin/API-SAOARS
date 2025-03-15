<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'type_equipment' => $this->type_equipment,
            'start' => $this->start,
            'image' => $this?->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

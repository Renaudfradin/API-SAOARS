<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttackDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'mp_cost' => $this->mp_cost,
            'type_atk' => $this->type_atk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

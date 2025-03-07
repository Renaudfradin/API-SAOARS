<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AbilityDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'descripton' => $this->descripton,
            'type' => $this->type,
            'start' => $this->start,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

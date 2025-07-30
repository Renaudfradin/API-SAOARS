<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class WeaponResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'element_weapons' => $this->element_weapons,
            'start' => $this->start,
            'image2' => Storage::disk('scaleway')->url($this->image2),
            'created_at' => $this->created_at,
        ];
    }
}

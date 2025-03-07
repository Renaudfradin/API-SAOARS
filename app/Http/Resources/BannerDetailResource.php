<?php

namespace App\Http\Resources;

use App\Models\Character;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerDetailResource extends JsonResource
{
    public function getcharacters($characterId)
    {
        return Character::whereIn('id', $characterId)->get();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'characters' => $this->getcharacters($this->characters),
            'from' => $this->from,
            'to' => $this->to,
            'img' => $this->img,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

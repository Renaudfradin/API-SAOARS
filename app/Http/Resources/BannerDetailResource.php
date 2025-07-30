<?php

namespace App\Http\Resources;

use App\Models\Character;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BannerDetailResource extends JsonResource
{
    public function getcharacters($characterId)
    {
        return Character::whereIn('id', $characterId)
            ->select('id', 'name', 'slug', 'element', 'image')
            ->get()
            ->toArray();
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
            'image' => Storage::disk('scaleway')->url($this->img),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

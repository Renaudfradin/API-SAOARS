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
            ->select('id', 'name', 'slug', 'element', 'image2')
            ->get()
            ->map(function ($character) {
                return [
                    'id' => $character->id,
                    'name' => $character->name,
                    'slug' => $character->slug,
                    'element' => $character->element,
                    'image2' => Storage::disk('scaleway')->url($character->image2),
                ];
            })
            ->toArray();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'characters' => $this->getcharacters($this->character_ids),
            'from' => $this->from?->format('Y-m-d'),
            'to' => $this->to?->format('Y-m-d'),
            'image' => Storage::disk('scaleway')->url($this->img),
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
        ];
    }
}

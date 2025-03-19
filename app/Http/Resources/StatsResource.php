<?php

namespace App\Http\Resources;

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\Weapon;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Character' => Character::count(),
            'Weapon' => Weapon::count(),
            'Attack' => Attack::count(),
            'Banner' => Banner::count(),
            'Equipment' => Equipment::count(),
            'Imagination' => Imagination::count(),
            'Ability' => Ability::count(),
        ];
    }
}

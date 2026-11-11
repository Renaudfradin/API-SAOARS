<?php

namespace Database\Factories;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Models\Attack;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CharacterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'description' => fake()->text(),
            'profile' => fake()->text(),
            'element' => Element::Terre,
            'weapon_type' => WeaponType::Épée,
            'atk1' => Attack::factory(),
            'atk2' => Attack::factory(),
            'atk3' => Attack::factory(),
            'hp' => fake()->randomDigit(),
            'mp' => fake()->randomDigit(),
            'atk' => fake()->randomDigit(),
            'matk' => fake()->randomDigit(),
            'def' => fake()->randomDigit(),
            'mdef' => fake()->randomDigit(),
            'crit' => fake()->randomDigit(),
            'spd' => fake()->randomDigit(),
            'ultime' => fake()->text(),
            'ultime_description' => fake()->text(),
            'enhance' => fake()->text(),
            'enhance_atk' => Attack::factory(),
            'enhance_atk2' => Attack::factory(),
            'start' => fake()->randomDigit(),
            'cost' => fake()->randomDigit(),
            'special_partner' => null,
            'image' => 'character/01K1DGKE5PS9B0Q3556JBBPKDB.png',
            'image2' => 'character/01K1DGKEP6YXJ0YPMEX0GFCZ0B.png',
        ];
    }
}

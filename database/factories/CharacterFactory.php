<?php

namespace Database\Factories;

use App\Enums\Element;
use App\Models\Attack;
use App\Models\Character;
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
            'element' => Element::Earth,
            'atk1' => Attack::class,
            'atk2' => Attack::class,
            'atk3' => Attack::class,
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
            'enhance_atk' => Attack::class,
            'enhance_atk2' => Attack::class,
            'start' => fake()->randomDigit(),
            'cost' => fake()->randomDigit(),
            'special_partner' => Character::class,
            'image' => 'character/01K1DGKE5PS9B0Q3556JBBPKDB.png',
            'image2' => 'character/01K1DGKEP6YXJ0YPMEX0GFCZ0B.png',
        ];
    }
}

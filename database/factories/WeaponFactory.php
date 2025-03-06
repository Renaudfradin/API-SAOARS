<?php

namespace Database\Factories;

use App\Enums\Element;
use App\Enums\WeaponType;
use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WeaponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'type' => WeaponType::Gauntlet,
            'element_weapons' => Element::Water,
            'hp' => fake()->randomDigit(),
            'mp' => fake()->randomDigit(),
            'atk' => fake()->randomDigit(),
            'matk' => fake()->randomDigit(),
            'def' => fake()->randomDigit(),
            'mdef' => fake()->randomDigit(),
            'crit' => fake()->randomDigit(),
            'spd' => fake()->randomDigit(),
            'effect_1' => fake()->text(),
            'effect_2' => fake()->text(),
            'effect_3' => fake()->text(),
            'characters_id' => Character::class,
            'start' => fake()->randomDigit(),
        ];
    }
}

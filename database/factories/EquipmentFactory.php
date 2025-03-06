<?php

namespace Database\Factories;

use App\Enums\Element;
use App\Enums\EquipmentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'type' => Element::Earth,
            'type_equipment' => EquipmentType::Armour,
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
            'start' => fake()->randomDigit(),
        ];
    }
}

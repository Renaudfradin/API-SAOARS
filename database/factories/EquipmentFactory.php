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
            'type' => EquipmentType::Armour,
            'type_equipment' => Element::Earth,
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
            'image' => 'equipment/01K1DFB627KZM762V6HM9HT6ET.jpg',
            'image2' => 'equipment/01K1DFB6RKRQH2FDQ4P30A0KJ2.png',
        ];
    }
}

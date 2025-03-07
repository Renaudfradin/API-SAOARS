<?php

namespace Database\Factories;

use App\Enums\AttackType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'description' => fake()->text(),
            'mp_cost' => fake()->randomDigit(),
            'type_atk' => AttackType::B,
        ];
    }
}

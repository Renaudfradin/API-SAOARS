<?php

namespace Database\Factories;

use App\Enums\AttackType;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'mp_cost' => fake()->randomDigit(),
            'type_atk' => AttackType::B,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\Element;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImaginationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->test(),
            'element' => Element::Neutral,
        ];
    }
}

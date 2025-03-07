<?php

namespace Database\Factories;

use App\Enums\Element;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImaginationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'description' => fake()->test(),
            'element' => Element::Neutral,
        ];
    }
}

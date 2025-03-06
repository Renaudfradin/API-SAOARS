<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AbilityFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'descripton' => fake()->text(),
            'type' => 'test',
            'start' => fake()->randomDigit(),
        ];
    }
}

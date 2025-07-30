<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => Str::slug(fake()->name()),
            'from' => '2025-03-08 00:00:00',
            'to' => '2025-03-08 00:00:00',
            'characters' => [1, 2, 3, 4, 5],
            'img' => 'banner/01K1D9Y9RGT9R6R5NSS9J17C3C.jpg',
        ];
    }
}

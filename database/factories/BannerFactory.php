<?php

namespace Database\Factories;

use App\Models\Character;
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
            'characters' => Character::class,
            'img' => fake()->imageUrl(640, 480, 'animals', true),
        ];
    }
}

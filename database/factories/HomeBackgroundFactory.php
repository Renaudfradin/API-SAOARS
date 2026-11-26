<?php

namespace Database\Factories;

use App\Models\HomeBackground;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeBackground>
 */
class HomeBackgroundFactory extends Factory
{
    protected $model = HomeBackground::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'fanadata_id' => fake()->unique()->numberBetween(1, 9000),
            'name' => ['fr' => $name, 'en' => $name],
            'world' => fake()->numberBetween(1, 6),
            'rarity' => fake()->numberBetween(1, 4),
        ];
    }
}

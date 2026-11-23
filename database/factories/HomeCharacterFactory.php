<?php

namespace Database\Factories;

use App\Models\HomeCharacter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomeCharacter>
 */
class HomeCharacterFactory extends Factory
{
    protected $model = HomeCharacter::class;

    public function definition(): array
    {
        $person = fake()->firstName();

        return [
            'fanadata_id' => fake()->unique()->numberBetween(1, 9000),
            'person_key' => strtolower($person),
            'person_name' => ['fr' => $person, 'en' => $person],
            'title' => ['fr' => fake()->words(3, true), 'en' => fake()->words(3, true)],
            'type' => fake()->numberBetween(1, 4),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\User;
use App\Models\Weapon;
use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->withProgressBar(1, fn () => User::factory(1)
            ->create([
                'name' => 'Test User',
                'email' => 'test@gmail.com',
            ])
        );
        $this->command->info('Admin user created.');

        $attack = Attack::factory()->create()->id;
        $character = Character::factory()->create([
            'atk1' => $attack,
            'atk2' => $attack,
            'atk3' => $attack,
            'enhance_atk' => $attack,
            'enhance_atk2' => $attack,
            'special_partner' => 1,
        ])->id;

        Character::factory(300)
            ->create([
                'atk1' => $attack,
                'atk2' => $attack,
                'atk3' => $attack,
                'enhance_atk' => $attack,
                'enhance_atk2' => $attack,
                'special_partner' => $character,
            ]);

        Weapon::factory(150)
            ->create([
                'characters_id' => $character,
            ]);

        Banner::factory(120)
            ->create([
                'characters' => $character,
            ]);

        Attack::factory(80)->create();
        Ability::factory(60)->create();
        // Imagination::factory(100)->create();
        Equipment::factory(120)->create();
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection;

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}

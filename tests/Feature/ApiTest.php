<?php

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Imagination;
use App\Models\Weapon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

it('returns the api home message', function () {
    $this->getJson('/api')
        ->assertOk()
        ->assertJsonStructure(['message']);
});

it('reports a healthy api and database', function () {
    $this->getJson('/api/health')
        ->assertOk()
        ->assertJsonPath('status', 'healthy')
        ->assertJsonPath('database', 'connected')
        ->assertJsonStructure(['timestamp']);
});

it('reports an unhealthy api when the database is disconnected', function () {
    DB::shouldReceive('connection')->andThrow(new PDOException('down'));

    $this->getJson('/api/health')
        ->assertServiceUnavailable()
        ->assertJsonPath('status', 'unhealthy')
        ->assertJsonPath('database', 'disconnected');
});

it('returns 429 when the api rate limit is exceeded', function () {
    config(['rate_limiting.api.limit' => 3]);
    RateLimiter::clear('api');

    for ($i = 0; $i < 3; $i++) {
        $this->getJson('/api')->assertOk();
    }

    $this->getJson('/api')
        ->assertStatus(429)
        ->assertJson([
            'message' => 'Trop de requêtes. Veuillez réessayer plus tard.',
        ])
        ->assertHeader('Retry-After');
});

it('lists and shows each catalog resource', function (string $model, string $index, string $show) {
    $record = $model::factory()->create(['slug' => 'fiche-connue']);

    $this->getJson($index)
        ->assertOk()
        ->assertJsonPath('data.0.slug', 'fiche-connue');

    $this->getJson($show.'/fiche-connue')
        ->assertOk()
        ->assertJsonPath('data.slug', 'fiche-connue');

    $this->getJson($show.'/absente')->assertNotFound();
})->with([
    'attacks' => [Attack::class, '/api/attacks', '/api/attack'],
    'abilities' => [Ability::class, '/api/abilities', '/api/ability'],
    'banners' => [Banner::class, '/api/banners', '/api/banner'],
    'characters' => [Character::class, '/api/characters', '/api/character'],
    'equipments' => [Equipment::class, '/api/equipments', '/api/equipment'],
    'imaginations' => [Imagination::class, '/api/imaginations', '/api/imagination'],
    'weapons' => [Weapon::class, '/api/weapons', '/api/weapon'],
]);

it('returns the requested locale and falls back to french', function () {
    $weapon = Weapon::factory()->create([
        'slug' => 'epee-celeste',
        'name' => 'Épée céleste',
    ]);
    $weapon->setTranslation('name', 'ja', '天涯比隣の剣');
    $weapon->save();

    $this->getJson('/api/weapon/epee-celeste?lang=ja')
        ->assertOk()
        ->assertJsonPath('data.name', '天涯比隣の剣')
        ->assertJsonPath('data.lang', 'ja');

    $this->getJson('/api/weapon/epee-celeste?lang=ko')
        ->assertOk()
        ->assertJsonPath('data.name', 'Épée céleste')
        ->assertJsonPath('data.lang', 'fr');
});

it('exposes five stat stages on a weapon', function () {
    $weapon = Weapon::factory()->create(['slug' => 'epee-a-cinq-paliers']);

    foreach (range(1, 5) as $stage) {
        $weapon->statStages()->create([
            'stage' => $stage,
            'hp' => 0,
            'mp' => 0,
            'atk' => 800 + ($stage * 100),
            'matk' => 700 + ($stage * 100),
            'def' => 0,
            'mdef' => 0,
            'crit' => 200 + $stage,
            'spd' => 0,
        ]);
    }

    $this->getJson('/api/weapon/epee-a-cinq-paliers')
        ->assertOk()
        ->assertJsonCount(5, 'data.stats')
        ->assertJsonPath('data.stats.0.stage', 1)
        ->assertJsonPath('data.stats.4.stage', 5)
        ->assertJsonPath('data.stats.4.atk', 1300);
});

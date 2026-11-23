<?php

use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\HomeBackground;
use App\Models\HomeCharacter;
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

it('returns a random banner set and a random character deck with artwork', function () {
    Banner::factory()->count(3)->create();
    Character::factory()->create([
        'slug' => 'kirito-aleatoire',
        'fanadata_id' => 121,
        'start' => 4,
    ]);
    Character::factory()->create([
        'slug' => 'personnage-trois-etoiles',
        'fanadata_id' => 12,
        'start' => 3,
    ]);

    $this->getJson('/api/banners/random?limit=2')
        ->assertOk()
        ->assertJsonCount(2, 'data');

    $this->getJson('/api/character/random')
        ->assertOk()
        ->assertJsonPath('data.slug', 'kirito-aleatoire')
        ->assertJsonPath('data.artwork', 'https://saoub.fanadata.com/assets/characters/artwork/character_121.png')
        ->assertJsonPath('data.artwork_large', 'https://saoub.fanadata.com/assets/characters/artwork/large/character_121.png')
        ->assertJsonPath('data.artwork_limit_break', 'https://saoub.fanadata.com/assets/characters/artwork/large/character_121_4.png');

    $this->getJson('/api/character/random?limit=2')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'kirito-aleatoire')
        ->assertJsonPath('data.0.start', 4)
        ->assertJsonPath('data.0.artwork_thumbnail', 'https://saoub.fanadata.com/assets/characters/artwork/thumbnail/character_121.png');
});

it('lists home gallery records and filters them', function () {
    HomeCharacter::factory()->create([
        'fanadata_id' => 205,
        'person_key' => 'lisbeth',
        'person_name' => ['fr' => 'Lisbeth', 'en' => 'Lisbeth'],
        'title' => ['fr' => 'Nuit sans sommeil', 'en' => 'Those Sleepless Nights'],
    ]);
    HomeCharacter::factory()->create(['person_key' => 'kirito']);
    HomeBackground::factory()->create([
        'fanadata_id' => 150,
        'world' => 6,
        'name' => ['fr' => 'Liaison entre les mondes', 'en' => 'Other World Bonds'],
    ]);
    HomeBackground::factory()->create(['world' => 1]);

    $this->getJson('/api/home-characters?person=lisbeth&lang=en')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.person', 'Lisbeth')
        ->assertJsonPath('data.0.name', 'Those Sleepless Nights')
        ->assertJsonPath('data.0.image', 'https://saoub.fanadata.com/assets/home/characters/thumbnail/home_character_205.png');

    $this->getJson('/api/home-backgrounds?world=6&lang=fr')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Liaison entre les mondes')
        ->assertJsonPath('data.0.world_name', 'etc.')
        ->assertJsonPath('data.0.image_large', 'https://saoub.fanadata.com/assets/home/backgrounds/home_background_150.jpg');

    $this->getJson('/api/home-backgrounds?per_page=200')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.per_page', 200)
        ->assertJsonPath('meta.last_page', 1);
});

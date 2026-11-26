<?php

use App\Models\Character;
use App\Models\HomeBackground;
use App\Models\HomeCharacter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

it('stores gallery images from fanadata and leaves missing files empty', function () {
    Storage::fake('scaleway');

    Http::fake([
        'https://saoub.fanadata.com/assets/characters/artwork/character_121.png' => Http::response('card', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/characters/artwork/thumbnail/character_121.png' => Http::response('thumb', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/characters/artwork/large/character_121.png' => Http::response('large', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/characters/artwork/large/character_121_4.png' => Http::response('', 404),
        'https://saoub.fanadata.com/assets/characters/artwork/thumbnail/character_121_4.png' => Http::response('lb-thumb', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/home/characters/thumbnail/home_character_205.png' => Http::response('home-thumb', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/home/characters/home_character_205.png' => Http::response('home-full', 200, ['Content-Type' => 'image/png']),
        'https://saoub.fanadata.com/assets/home/backgrounds/thumbnail/home_background_150.jpg' => Http::response('', 404),
        'https://saoub.fanadata.com/assets/home/backgrounds/home_background_150.jpg' => Http::response('bg', 200, ['Content-Type' => 'image/jpeg']),
    ]);

    $character = Character::factory()->create([
        'fanadata_id' => 121,
        'start' => 4,
    ]);
    $homeCharacter = HomeCharacter::factory()->create(['fanadata_id' => 205]);
    $background = HomeBackground::factory()->create(['fanadata_id' => 150]);

    $this->artisan('galleries:import')->assertOk();

    $character->refresh();
    $homeCharacter->refresh();
    $background->refresh();

    expect($character->artwork)->toBe('galleries/characters/artwork/character_121.png')
        ->and($character->artwork_thumbnail)->toBe('galleries/characters/artwork/thumbnail/character_121.png')
        ->and($character->artwork_large)->toBe('galleries/characters/artwork/large/character_121.png')
        ->and($character->artwork_limit_break)->toBeNull()
        ->and($character->artwork_limit_break_thumbnail)->toBe('galleries/characters/artwork/thumbnail/character_121_4.png')
        ->and($character->artworkUrl())->toBe(Storage::disk('scaleway')->url($character->artwork))
        ->and($character->artworkUrl('large', '_4'))->toBe('https://saoub.fanadata.com/assets/characters/artwork/large/character_121_4.png')
        ->and($homeCharacter->image)->toBe('galleries/home/characters/thumbnail/home_character_205.png')
        ->and($homeCharacter->image_large)->toBe('galleries/home/characters/home_character_205.png')
        ->and($homeCharacter->thumbnailUrl())->toBe(Storage::disk('scaleway')->url($homeCharacter->image))
        ->and($background->image)->toBeNull()
        ->and($background->image_large)->toBe('galleries/home/backgrounds/home_background_150.jpg')
        ->and($background->thumbnailUrl())->toBe('https://saoub.fanadata.com/assets/home/backgrounds/thumbnail/home_background_150.jpg')
        ->and($background->imageUrl())->toBe(Storage::disk('scaleway')->url($background->image_large));

    Storage::disk('scaleway')->assertExists('galleries/characters/artwork/character_121.png');
    Storage::disk('scaleway')->assertExists('galleries/home/characters/home_character_205.png');
    Storage::disk('scaleway')->assertExists('galleries/home/backgrounds/home_background_150.jpg');
    Storage::disk('scaleway')->assertMissing('galleries/characters/artwork/large/character_121_4.png');
    Storage::disk('scaleway')->assertMissing('galleries/home/backgrounds/thumbnail/home_background_150.jpg');
});

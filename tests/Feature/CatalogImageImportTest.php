<?php

use App\Models\Banner;
use App\Models\Equipment;
use App\Models\Weapon;
use App\Support\Locales;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

it('stores banner, equipment and weapon images without replacing a custom file', function () {
    Storage::fake('scaleway');

    $fakes = [];

    foreach (array_unique(Locales::FANADATA_SUFFIX) as $suffix) {
        $fakes['https://saoub.fanadata.com/assets/banners/'.$suffix.'/scout_4.jpg'] = $suffix === 'ja'
            ? Http::response('', 404)
            : Http::response('banner-'.$suffix, 200, ['Content-Type' => 'image/jpeg']);
        $fakes['https://saoub.fanadata.com/assets/banners/'.$suffix.'/scout_9.jpg'] = Http::response('nope', 200, ['Content-Type' => 'image/jpeg']);
    }

    $fakes['https://saoub.fanadata.com/assets/equipments/stuff_31.jpg'] = Http::response('portrait', 200, ['Content-Type' => 'image/jpeg']);
    $fakes['https://saoub.fanadata.com/assets/equipments/thumbnail/stuff_31.png'] = Http::response('thumb', 200, ['Content-Type' => 'image/png']);
    $fakes['https://saoub.fanadata.com/assets/weapons/render/weapon_6.png'] = Http::response('', 404);
    $fakes['https://saoub.fanadata.com/assets/weapons/thumbnail/weapon_6.png'] = Http::response('', 404);
    $fakes['https://saoub.fanadata.com/assets/weapons/thumbnail/stuff_6.png'] = Http::response('blade', 200, ['Content-Type' => 'image/png']);

    Http::fake($fakes);

    $banner = Banner::factory()->create([
        'fanadata_id' => 4,
        'img' => 'banner/01K1D9Y9RGT9R6R5NSS9J17C3C.jpg',
    ]);
    $custom = Banner::factory()->create([
        'fanadata_id' => 9,
        'img' => 'banner/custom-art.jpg',
    ]);
    $equipment = Equipment::factory()->create([
        'fanadata_id' => 31,
        'image' => null,
        'image2' => null,
    ]);
    $weapon = Weapon::factory()->create([
        'fanadata_id' => 6,
        'image' => null,
        'image2' => null,
    ]);
    $kept = Weapon::factory()->create([
        'fanadata_id' => 8,
        'image' => 'weapon/custom.jpg',
        'image2' => 'weapon/custom-2.jpg',
    ]);

    $this->artisan('galleries:import', ['--type' => 'banners'])->assertOk();
    $this->artisan('galleries:import', ['--type' => 'equipments'])->assertOk();
    $this->artisan('galleries:import', ['--type' => 'weapons'])->assertOk();

    $banner->refresh();
    $equipment->refresh();
    $weapon->refresh();

    expect($banner->img)->toBe('banner/fr/scout_4.jpg')
        ->and($banner->artworkUrl('fr'))->toBe(Storage::disk('scaleway')->url('banner/fr/scout_4.jpg'))
        ->and($banner->artworkUrl('en'))->toBe(Storage::disk('scaleway')->url('banner/en/scout_4.jpg'))
        ->and($banner->artworkFallbackUrl())->toBe(Storage::disk('scaleway')->url('banner/en/scout_4.jpg'))
        ->and($custom->fresh()->img)->toBe('banner/custom-art.jpg')
        ->and($equipment->image)->toBe('equipment/stuff_31.jpg')
        ->and($equipment->image2)->toBe('equipment/thumbnail/stuff_31.png')
        ->and($equipment->portraitUrl())->toBe(Storage::disk('scaleway')->url('equipment/stuff_31.jpg'))
        ->and($equipment->thumbnailUrl())->toBe(Storage::disk('scaleway')->url('equipment/thumbnail/stuff_31.png'))
        ->and($weapon->image)->toBe('weapon/thumbnail/stuff_6.png')
        ->and($weapon->image2)->toBeNull()
        ->and($kept->fresh()->image)->toBe('weapon/custom.jpg');

    Storage::disk('scaleway')->assertExists('banner/fr/scout_4.jpg');
    Storage::disk('scaleway')->assertExists('banner/en/scout_4.jpg');
    Storage::disk('scaleway')->assertMissing('banner/ja/scout_4.jpg');
    Storage::disk('scaleway')->assertMissing('banner/fr/scout_9.jpg');
    Storage::disk('scaleway')->assertExists('equipment/stuff_31.jpg');
    Storage::disk('scaleway')->assertExists('weapon/thumbnail/stuff_6.png');
    Storage::disk('scaleway')->assertMissing('weapon/render/weapon_6.png');
    Storage::disk('scaleway')->assertMissing('weapon/render/weapon_8.png');
});

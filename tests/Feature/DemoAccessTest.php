<?php

use App\Enums\Role;
use App\Filament\Auth\Login;
use App\Filament\Resources\AttackResource\Pages\CreateAttack;
use App\Filament\Resources\AttackResource\Pages\EditAttack;
use App\Filament\Resources\AttackResource\Pages\ListAttacks;
use App\Filament\Resources\BannerResource\Pages\ListBanners;
use App\Filament\Resources\CharacterResource\Pages\ListCharacters;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

it('keeps a demo account on read only catalog pages', function () {
    $demo = User::factory()->create([
        'email' => 'demo@gmail.com',
        'role' => Role::Demo->value,
        'email_verified_at' => now(),
    ]);
    $attack = Attack::factory()->create();

    expect($demo->canAccessPanel(Filament::getPanel('admin')))->toBeTrue()
        ->and($demo->can('view', $attack))->toBeTrue()
        ->and($demo->can('create', Attack::class))->toBeFalse()
        ->and($demo->can('update', $attack))->toBeFalse()
        ->and($demo->can('delete', $attack))->toBeFalse()
        ->and($demo->can('viewAny', User::class))->toBeFalse();

    $this->actingAs($demo);
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    Livewire::test(ListAttacks::class)->assertOk();
    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])->assertForbidden();
    Livewire::test(CreateAttack::class)->assertForbidden();
});

it('filters banners by date and character', function () {
    $this->actingAs(User::factory()->create([
        'email' => 'admin@gmail.com',
        'role' => Role::Admin->value,
        'email_verified_at' => now(),
    ]));
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $character = Character::factory()->create();
    $listed = Banner::factory()->create([
        'character_ids' => [$character->id],
        'from' => '2025-03-01 00:00:00',
        'to' => '2025-03-31 00:00:00',
    ]);
    $linked = Banner::factory()->create([
        'character_ids' => [],
        'from' => '2025-04-01 00:00:00',
        'to' => '2025-04-30 00:00:00',
    ]);
    DB::table('characters')->where('id', $character->id)->update(['banner_id' => $linked->id]);
    $other = Banner::factory()->create([
        'character_ids' => [],
        'from' => '2024-01-01 00:00:00',
        'to' => '2024-01-02 00:00:00',
    ]);

    Livewire::test(ListBanners::class)
        ->filterTable('character', $character->id)
        ->assertCanSeeTableRecords([$listed, $linked])
        ->assertCanNotSeeTableRecords([$other])
        ->filterTable('date', ['date' => '2025-03-15'])
        ->assertCanSeeTableRecords([$listed])
        ->assertCanNotSeeTableRecords([$linked, $other]);
});

it('links synced banner characters and clears the ones that left', function () {
    $kept = Character::factory()->create(['fanadata_id' => 501]);
    $removed = Character::factory()->create(['fanadata_id' => 502]);
    $banner = Banner::factory()->create([
        'fanadata_id' => 12,
        'character_ids' => [$kept->id, $removed->id],
    ]);
    $kept->update(['banner_id' => $banner->id]);
    $removed->update(['banner_id' => $banner->id]);

    Http::fake([
        'https://api.fanadata.com/saoub/banners/12/' => Http::response([
            'id' => 12,
            'name_fr' => 'Bannière liée',
            'name_en' => 'Linked banner',
            'started_at' => '2025-01-01T00:00:00Z',
            'ended_at' => '2025-02-01T00:00:00Z',
            'characters' => [
                ['id' => 501],
            ],
        ]),
    ]);

    $this->artisan('fanadata:sync', ['--type' => 'banners', '--id' => 12])
        ->assertOk();

    expect($kept->fresh()->banner_id)->toBe($banner->id)
        ->and($removed->fresh()->banner_id)->toBeNull()
        ->and($banner->fresh()->character_ids)->toBe([$kept->id]);
});

it('shows the banner a character is attached to', function () {
    $this->actingAs(User::factory()->create([
        'email' => 'admin@gmail.com',
        'role' => Role::Admin->value,
        'email_verified_at' => now(),
    ]));
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $character = Character::factory()->create();
    $from = Banner::factory()->create([
        'name' => ['fr' => 'Ancienne bannière', 'en' => 'Old banner'],
        'character_ids' => [$character->id],
    ]);
    $to = Banner::factory()->create([
        'name' => ['fr' => 'Bannière visible', 'en' => 'Bannière visible'],
        'character_ids' => [],
    ]);
    $character->update(['banner_id' => $from->id]);
    $character->update(['banner_id' => $to->id]);

    expect($from->fresh()->character_ids)->not->toContain($character->id)
        ->and($to->fresh()->character_ids)->toContain($character->id);

    Livewire::test(ListCharacters::class)
        ->assertCanSeeTableRecords([$character])
        ->assertSee('Bannière visible');
});

it('stops filament login after the configured attempt limit', function () {
    config(['rate_limiting.login.limit' => 2]);
    RateLimiter::clear(md5('login127.0.0.1'));
    Filament::setCurrentPanel(Filament::getPanel('admin'));

    $login = Livewire::test(Login::class)
        ->fillForm([
            'email' => 'demo@gmail.com',
            'password' => 'mauvais-mot-de-passe',
        ]);

    $login->call('authenticate');
    $login->call('authenticate');
    $login->call('authenticate')->assertNotified();
});

<?php

use App\Enums\AttackType;
use App\Enums\Role;
use App\Filament\Resources\AttackResource\Pages\CreateAttack;
use App\Filament\Resources\AttackResource\Pages\EditAttack;
use App\Filament\Resources\AttackResource\Pages\ListAttacks;
use App\Filament\Resources\BannerResource\Pages\EditBanner;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create([
        'email' => 'admin@gmail.com',
        'role' => Role::Admin->value,
        'email_verified_at' => now(),
    ]));

    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('fills translatable fields with the active locale string', function () {
    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair',
        'description' => ['fr' => 'Une attaque', 'en' => 'An attack'],
        'type_atk' => AttackType::B,
    ]);

    $component = Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->assertSchemaStateSet([
            'name' => 'Éclair',
            'description' => 'Une attaque',
        ]);

    expect($component->get('data.name'))->toBeString()->not->toBe('[object Object]');
    expect($component->get('data.description'))->toBeString();

    $component
        ->set('activeLocale', 'en')
        ->assertSchemaStateSet([
            'name' => 'Lightning',
            'description' => 'An attack',
        ]);
});

it('saves only the active locale and keeps the other translations', function () {
    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair',
        'description' => ['fr' => 'Une attaque', 'en' => 'An attack'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->fillForm([
            'name' => 'Éclair modifié',
            'description' => 'Texte modifié',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $attack->refresh();

    expect($attack->getTranslation('name', 'fr', false))->toBe('Éclair modifié')
        ->and($attack->getTranslation('description', 'fr', false))->toBe('Texte modifié')
        ->and($attack->getTranslation('name', 'en', false))->toBe('Lightning')
        ->and($attack->getTranslation('description', 'en', false))->toBe('An attack');

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->set('activeLocale', 'en')
        ->fillForm([
            'name' => 'Bolt',
            'description' => 'A strike',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $attack->refresh();

    expect($attack->getTranslation('name', 'en', false))->toBe('Bolt')
        ->and($attack->getTranslation('description', 'en', false))->toBe('A strike')
        ->and($attack->getTranslation('name', 'fr', false))->toBe('Éclair modifié');
});

it('stores a created attack in the selected locale', function () {
    Livewire::test(CreateAttack::class)
        ->set('activeLocale', 'en')
        ->fillForm([
            'name' => 'Lightning',
            'slug' => 'lightning',
            'description' => 'An attack',
            'mp_cost' => 12,
            'type_atk' => AttackType::B->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $attack = Attack::query()->where('slug', 'lightning')->first();

    expect($attack)->not->toBeNull()
        ->and($attack->getTranslation('name', 'en', false))->toBe('Lightning')
        ->and($attack->getTranslation('description', 'en', false))->toBe('An attack')
        ->and($attack->getTranslation('name', 'fr', false))->toBe('');
});

it('keeps a customized banner form on locale strings', function () {
    $banner = Banner::factory()->create([
        'name' => ['fr' => 'Bannière été', 'en' => 'Summer banner'],
        'slug' => 'banniere-ete',
        'character_ids' => [],
    ]);

    Livewire::test(EditBanner::class, ['record' => $banner->getKey()])
        ->assertSchemaStateSet([
            'name' => 'Bannière été',
            'character_ids' => [],
        ])
        ->set('activeLocale', 'en')
        ->assertSchemaStateSet([
            'name' => 'Summer banner',
        ]);
});

it('shows the locale string in the list edit action', function () {
    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair-liste',
        'description' => ['fr' => 'Une attaque', 'en' => 'An attack'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(ListAttacks::class)
        ->assertCanSeeTableRecords([$attack])
        ->assertSee('Éclair')
        ->mountTableAction('edit', $attack)
        ->assertSchemaStateSet([
            'name' => 'Éclair',
            'description' => 'Une attaque',
        ]);
});

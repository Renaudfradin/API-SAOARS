<?php

use App\Enums\AttackType;
use App\Enums\Role;
use App\Filament\Resources\AttackResource\Pages\CreateAttack;
use App\Filament\Resources\AttackResource\Pages\EditAttack;
use App\Models\Attack;
use App\Models\User;
use App\Services\Translation\CatalogContentTranslator;
use App\Support\Locales;
use Filament\Facades\Filament;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create([
        'email' => 'admin@gmail.com',
        'role' => Role::Admin->value,
        'email_verified_at' => now(),
    ]));

    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('shows the translate button on attack forms', function () {
    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair'],
        'slug' => 'eclair-bouton',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->assertSee('Traduire')
        ->assertActionExists('translateCatalog');

    Livewire::test(CreateAttack::class)
        ->assertSee('Traduire')
        ->assertActionExists('translateCatalog');
});

it('asks for a cursor api key before translating', function () {
    config(['services.cursor.api_key' => null]);

    expect(fn () => app(CatalogContentTranslator::class)->begin([
        'name' => 'Éclair',
        'description' => 'Une attaque',
    ]))->toThrow(RuntimeException::class, 'CURSOR_API_KEY');
});

it('keeps empty french fields empty and falls back when a translation is blank', function () {
    $raw = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $raw[$locale] = [
            'name' => '',
            'description' => '['.$locale.'] ignoré',
        ];
    }

    $translations = app(CatalogContentTranslator::class)->apply([
        'name' => 'Éclair',
        'description' => '',
    ], $raw);

    expect($translations['en']['name'])->toBe('Éclair')
        ->and($translations['en']['description'])->toBe('')
        ->and($translations['ja']['description'])->toBe('');
});

it('translates the french attack into the other languages', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.translation_timeout' => 5,
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    $translations = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $translations[$locale] = [
            'name' => '['.$locale.'] Éclair',
            'description' => '['.$locale.'] Une attaque',
        ];
    }

    Http::fake(function ($request) use ($translations) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        if ($request->method() === 'POST' && $path === '/v1/agents') {
            expect($request['model']['id'])->toBe('gpt-5.4-nano')
                ->and($request['prompt']['text'])->toContain('Éclair')
                ->and($request['prompt']['text'])->toContain('en, zh, ko, ja, de, es');

            return Http::response([
                'agent' => ['id' => 'bc-test', 'url' => 'https://cursor.com/agents/bc-test'],
                'run' => ['id' => 'run-test'],
            ], 201);
        }

        if ($request->method() === 'GET') {
            return Http::response([
                'status' => 'FINISHED',
                'result' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        return Http::response([], 204);
    });

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Old lightning'],
        'slug' => 'eclair',
        'description' => ['fr' => 'Une attaque', 'en' => 'Old attack'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->call('translateCatalogFromFrench')
        ->assertSet('translationAgentId', 'bc-test')
        ->assertSet('activeLocale', 'fr')
        ->assertSet('data.name', 'Éclair')
        ->assertNotified('Traduction lancée')
        ->call('pollCatalogTranslation')
        ->assertSet('translationAgentId', null)
        ->assertSet('activeLocale', 'en')
        ->assertSet('data.name', '[en] Éclair')
        ->assertNotified('Traduction réussie')
        ->set('activeLocale', 'ja')
        ->assertSet('data.name', '[ja] Éclair')
        ->call('save')
        ->assertHasNoFormErrors();

    $attack->refresh();

    expect($attack->getTranslation('name', 'fr', false))->toBe('Éclair')
        ->and($attack->getTranslation('description', 'fr', false))->toBe('Une attaque')
        ->and($attack->getTranslation('name', 'en', false))->toBe('[en] Éclair')
        ->and($attack->getTranslation('description', 'ja', false))->toBe('[ja] Une attaque')
        ->and($attack->slug)->toBe('eclair');

    foreach (Locales::ALL as $locale) {
        if ($locale === 'fr') {
            continue;
        }

        expect($attack->getTranslation('name', $locale, false))->toBe('['.$locale.'] Éclair');
    }
});

it('keeps the translation when deleting the cursor agent times out', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    $translations = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $translations[$locale] = [
            'name' => '['.$locale.'] Éclair',
            'description' => '['.$locale.'] Une attaque',
        ];
    }

    Http::fake(function ($request) use ($translations) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        if ($request->method() === 'POST' && $path === '/v1/agents') {
            return Http::response([
                'agent' => ['id' => 'bc-timeout', 'url' => 'https://cursor.com/agents/bc-timeout'],
                'run' => ['id' => 'run-timeout'],
            ], 201);
        }

        if ($request->method() === 'GET') {
            return Http::response([
                'status' => 'FINISHED',
                'result' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        throw new ConnectionException('cURL error 28: Operation timed out after 30007 milliseconds with 0 bytes received');
    });

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair'],
        'slug' => 'eclair-timeout',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->call('translateCatalogFromFrench')
        ->call('pollCatalogTranslation')
        ->assertSet('translationAgentId', null)
        ->assertSet('activeLocale', 'en')
        ->assertSet('data.name', '[en] Éclair')
        ->assertNotified('Traduction réussie');
});

it('retries the translation follow-up when cursor does not answer in time', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    Http::fake(function ($request) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        if ($request->method() === 'POST' && $path === '/v1/agents') {
            return Http::response([
                'agent' => ['id' => 'bc-slow', 'url' => 'https://cursor.com/agents/bc-slow'],
                'run' => ['id' => 'run-slow'],
            ], 201);
        }

        throw new ConnectionException('cURL error 28: Operation timed out after 30007 milliseconds with 0 bytes received for https://api.cursor.com/v1/agents/bc-slow/runs/run-slow');
    });

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair'],
        'slug' => 'eclair-lent',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->call('translateCatalogFromFrench')
        ->call('pollCatalogTranslation')
        ->assertSet('translationAgentId', 'bc-slow')
        ->assertSet('activeLocale', 'fr')
        ->assertSet('data.name', 'Éclair')
        ->assertNotified('Traduction en cours');
});

it('fills only the empty translations when that option is chosen', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.translation_timeout' => 5,
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    $translations = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $translations[$locale] = [
            'name' => '['.$locale.'] Éclair',
            'description' => '['.$locale.'] Une attaque',
        ];
    }

    Http::fake(function ($request) use ($translations) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        if ($request->method() === 'POST' && $path === '/v1/agents') {
            return Http::response([
                'agent' => ['id' => 'bc-empty', 'url' => 'https://cursor.com/agents/bc-empty'],
                'run' => ['id' => 'run-empty'],
            ], 201);
        }

        if ($request->method() === 'GET') {
            return Http::response([
                'status' => 'FINISHED',
                'result' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        return Http::response([], 204);
    });

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair-vides',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->callAction('translateCatalog', [
            'provider' => 'cursor',
            'only_empty' => true,
        ])
        ->call('pollCatalogTranslation')
        ->assertSet('activeLocale', 'en')
        ->assertSet('data.name', 'Lightning')
        ->assertSet('data.description', '[en] Une attaque')
        ->set('activeLocale', 'ja')
        ->assertSet('data.name', '[ja] Éclair')
        ->call('save')
        ->assertHasNoFormErrors();

    $attack->refresh();

    expect($attack->getTranslation('name', 'en', false))->toBe('Lightning')
        ->and($attack->getTranslation('description', 'en', false))->toBe('[en] Une attaque')
        ->and($attack->getTranslation('name', 'ja', false))->toBe('[ja] Éclair')
        ->and($attack->getTranslation('name', 'fr', false))->toBe('Éclair');
});

it('shows a language that was empty when the other languages were already filled', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    $translations = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $translations[$locale] = [
            'name' => '['.$locale.'] Éclair',
            'description' => '['.$locale.'] Une attaque',
        ];
    }

    Http::fake(function ($request) use ($translations) {
        $path = (string) parse_url($request->url(), PHP_URL_PATH);

        if ($request->method() === 'POST' && $path === '/v1/agents') {
            return Http::response([
                'agent' => ['id' => 'bc-missing', 'url' => 'https://cursor.com/agents/bc-missing'],
                'run' => ['id' => 'run-missing'],
            ], 201);
        }

        if ($request->method() === 'GET') {
            return Http::response([
                'status' => 'FINISHED',
                'result' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        return Http::response([], 204);
    });

    $attack = Attack::factory()->create([
        'name' => [
            'fr' => 'Éclair',
            'en' => 'Lightning',
            'zh' => '闪电',
        ],
        'slug' => 'eclair-manquant',
        'description' => [
            'fr' => 'Une attaque',
            'en' => 'An attack',
            'zh' => '一次攻击',
        ],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->callAction('translateCatalog', [
            'provider' => 'cursor',
            'only_empty' => true,
        ])
        ->call('pollCatalogTranslation')
        ->assertSet('activeLocale', 'ko')
        ->assertSet('data.name', '[ko] Éclair')
        ->assertSet('data.description', '[ko] Une attaque')
        ->set('activeLocale', 'ja')
        ->assertSet('data.name', '[ja] Éclair')
        ->assertSet('data.description', '[ja] Une attaque')
        ->call('save')
        ->assertHasNoFormErrors();

    $attack->refresh();

    expect($attack->getTranslation('name', 'en', false))->toBe('Lightning')
        ->and($attack->getTranslation('name', 'ja', false))->toBe('[ja] Éclair')
        ->and($attack->getTranslation('name', 'ko', false))->toBe('[ko] Éclair')
        ->and($attack->getTranslation('description', 'ja', false))->toBe('[ja] Une attaque')
        ->and($attack->getTranslation('name', 'fr', false))->toBe('Éclair');
});

it('translates from the saved french text while another language is open', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'cursor',
    ]);

    $translations = [];

    foreach (['en', 'zh', 'ko', 'ja', 'de', 'es'] as $locale) {
        $translations[$locale] = [
            'name' => '['.$locale.'] Éclair',
            'description' => '['.$locale.'] Une attaque',
        ];
    }

    Http::fake(function ($request) use ($translations) {
        if ($request->method() === 'GET') {
            return Http::response([
                'status' => 'FINISHED',
                'result' => json_encode($translations, JSON_UNESCAPED_UNICODE),
            ]);
        }

        if ($request->method() === 'POST') {
            return Http::response([
                'agent' => ['id' => 'bc-ja', 'url' => 'https://cursor.com/agents/bc-ja'],
                'run' => ['id' => 'run-ja'],
            ], 201);
        }

        return Http::response([], 204);
    });

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair-depuis-ja',
        'description' => ['fr' => 'Une attaque', 'en' => 'An attack'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->set('activeLocale', 'ja')
        ->call('translateCatalogFromFrench', null, true)
        ->call('pollCatalogTranslation')
        ->assertSet('activeLocale', 'ja')
        ->assertSet('data.name', '[ja] Éclair')
        ->assertSet('data.description', '[ja] Une attaque');
});

it('does not call the translator when every field is already filled', function () {
    Http::fake();

    $filled = [];

    foreach (Locales::ALL as $locale) {
        $filled['name'][$locale] = 'Nom '.$locale;
        $filled['description'][$locale] = 'Texte '.$locale;
    }

    $attack = Attack::factory()->create([
        'name' => $filled['name'],
        'slug' => 'eclair-complet',
        'description' => $filled['description'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->call('translateCatalogFromFrench', null, true)
        ->assertNotified('Rien à traduire')
        ->assertSet('data.name', 'Nom fr');

    Http::assertNothingSent();

    $attack->refresh();

    expect($attack->getTranslation('name', 'en', false))->toBe('Nom en');
});

it('warns when the french text is empty', function () {
    Http::fake();

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair'],
        'slug' => 'eclair-vide',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    Livewire::test(EditAttack::class, ['record' => $attack->getKey()])
        ->fillForm([
            'name' => '',
            'description' => '',
        ])
        ->call('translateCatalogFromFrench')
        ->assertNotified('Texte français manquant');

    Http::assertNothingSent();
});

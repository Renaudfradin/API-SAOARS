<?php

use App\Enums\AttackType;
use App\Models\Attack;
use App\Support\Locales;
use Illuminate\Support\Facades\Http;

it('fills only empty translatable fields with cursor and announces the end', function () {
    config([
        'services.cursor.api_key' => 'cursor_test',
        'services.cursor.base_url' => 'https://api.cursor.com',
        'services.cursor.translation_model' => 'gpt-5.4-nano',
        'services.cursor.translation_timeout' => 5,
        'services.cursor.poll_interval' => 0,
        'translation.provider' => 'openai',
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
            expect($request['prompt']['text'])->toContain('Éclair')
                ->and($request['prompt']['text'])->toContain('Une attaque')
                ->and($request['prompt']['text'])->not->toContain('Déjà traduit');

            return Http::response([
                'agent' => ['id' => 'bc-empty-catalog', 'url' => 'https://cursor.com/agents/bc-empty-catalog'],
                'run' => ['id' => 'run-empty-catalog'],
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

    $complete = [];

    foreach (Locales::ALL as $locale) {
        $complete[$locale] = '['.$locale.'] Déjà traduit';
    }

    Attack::factory()->create([
        'name' => $complete,
        'slug' => 'deja-traduit',
        'description' => $complete,
        'type_atk' => AttackType::B,
    ]);

    $attack = Attack::factory()->create([
        'name' => ['fr' => 'Éclair', 'en' => 'Lightning'],
        'slug' => 'eclair-vides',
        'description' => ['fr' => 'Une attaque'],
        'type_atk' => AttackType::B,
    ]);

    $this->artisan('catalog:translate-empty')
        ->expectsOutputToContain('Attaque Éclair : envoi à Cursor (name, description).')
        ->expectsOutputToContain('Fiches mises à jour : 1')
        ->expectsOutputToContain('Champs remplis : 11')
        ->expectsOutputToContain('Fiches déjà complètes : 1')
        ->expectsOutputToContain('Échecs : 0')
        ->expectsOutputToContain('Les traductions sont terminées.')
        ->assertSuccessful();

    $attack->refresh();

    expect($attack->getTranslation('name', 'fr', false))->toBe('Éclair')
        ->and($attack->getTranslation('name', 'en', false))->toBe('Lightning')
        ->and($attack->getTranslation('description', 'en', false))->toBe('[en] Une attaque')
        ->and($attack->getTranslation('name', 'ja', false))->toBe('[ja] Éclair')
        ->and($attack->getTranslation('description', 'de', false))->toBe('[de] Une attaque');

    Http::assertSentCount(3);
});

it('rejects an unknown model before calling cursor', function () {
    Http::fake();

    $this->artisan('catalog:translate-empty', ['--model' => 'User'])
        ->expectsOutputToContain('Modèle inconnu')
        ->assertFailed();

    Http::assertNothingSent();
});

<?php

use App\Models\Weapon;
use Illuminate\Support\Facades\Http;

it('merges a fanadata weapon onto the french record and stores five stages', function () {
    Weapon::factory()->create([
        'name' => 'Épée céleste',
        'slug' => 'epee-celeste',
        'atk' => 1,
        'fanadata_id' => null,
    ]);

    Http::fake([
        'https://api.fanadata.com/saoub/weapons/375/' => Http::response(weaponPayload()),
    ]);

    $this->artisan('fanadata:sync', ['--type' => 'weapons', '--id' => 375])
        ->assertOk();

    $weapon = Weapon::query()->where('slug', 'epee-celeste')->first();

    expect(Weapon::query()->count())->toBe(1)
        ->and($weapon->fanadata_id)->toBe(375)
        ->and($weapon->getTranslation('name', 'ja'))->toBe('天涯比隣の剣')
        ->and($weapon->getTranslation('name', 'zh'))->toBe('天涯比鄰劍')
        ->and($weapon->getTranslation('name', 'fr'))->toBe('Épée céleste')
        ->and($weapon->statStages)->toHaveCount(5)
        ->and($weapon->fresh()->atk)->toBe(1253)
        ->and($weapon->statStages->firstWhere('stage', 1)->atk)->toBe(872)
        ->and($weapon->statStages->firstWhere('stage', 5)->atk)->toBe(1253);
});

function weaponPayload(): array
{
    $stats = [];

    foreach ([872, 967, 1062, 1157, 1253] as $index => $atk) {
        $stats[] = [
            'lb' => $index,
            'max_hp' => 0,
            'max_mp' => 0,
            'max_atk' => $atk,
            'max_matk' => 700 + $index,
            'max_def' => 0,
            'max_mdef' => 0,
            'max_crit' => 280 + $index,
            'max_spd' => 0,
        ];
    }

    return [
        'id' => 375,
        'element_type' => 'Void',
        'weapon_type' => 'Sword',
        'name_en' => 'Celestial Sword',
        'name_ja' => '天涯比隣の剣',
        'name_ko' => '천애비린의 검',
        'name_tw' => '天涯比鄰劍',
        'name_fr' => 'Épée céleste',
        'name_de' => 'Himmelsschwert',
        'name_es' => 'Espada Celestial',
        'rarity' => 4,
        'stats' => $stats,
        'battle_skills' => [
            [
                'slot_order' => 1,
                'lb' => 0,
                'battle_skill' => [
                    'text_fr' => 'Augmente ATK et CRIT de 15%',
                    'text_en' => 'Increases ATK & CRIT by 15%',
                    'text_ja' => 'ATKとCRITが15%増加する',
                    'text_ko' => 'ATK와 CRIT가 15% 증가한다',
                    'text_tw' => 'ATK和CRIT增加15%',
                    'text_de' => 'Erhöht ATK & CRIT um 15%',
                    'text_es' => 'ATK y CRIT +15%',
                ],
            ],
        ],
    ];
}

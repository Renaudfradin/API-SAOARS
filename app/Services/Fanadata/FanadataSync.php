<?php

namespace App\Services\Fanadata;

use App\Enums\AttackType;
use App\Models\Ability;
use App\Models\Attack;
use App\Models\Banner;
use App\Models\Character;
use App\Models\Equipment;
use App\Models\Weapon;
use Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FanadataSync
{
    /**
     * @var array<string, string>
     */
    private const ELEMENTS = [
        'void' => 'neutral',
        'none' => 'neutral',
        'neutral' => 'neutral',
        'non-elemental' => 'neutral',
        'fire' => 'fire',
        'water' => 'water',
        'wind' => 'wind',
        'earth' => 'earth',
        'light' => 'light',
        'holy' => 'light',
        'dark' => 'darkness',
        'darkness' => 'darkness',
    ];

    /**
     * @var array<string, string>
     */
    private const WEAPON_TYPES = [
        'sword' => 'sword',
        'rapier' => 'rapier',
        'dagger' => 'dagger',
        'bow' => 'bow',
        'dual swords' => 'dual swords',
        'dual sword' => 'dual swords',
        'dual blades' => 'dual swords',
        'gauntlet' => 'gauntlet',
        'rifle' => 'rifle',
        'mace' => 'mace',
    ];

    /**
     * @var array<string, string>
     */
    private const EQUIPMENT_TYPES = [
        'armor' => 'armour',
        'armour' => 'armour',
        'accessory' => 'accessory',
    ];

    public function __construct(private FanadataClient $client) {}

    /**
     * @return array<string, int>
     */
    public function run(string $type = 'all', ?int $id = null): array
    {
        $counts = [
            'weapons' => 0,
            'equipments' => 0,
            'characters' => 0,
            'banners' => 0,
            'attacks' => 0,
            'abilities' => 0,
        ];

        if ($type === 'weapon-skills') {
            $counts['weapons'] = $this->storeWeaponSkills();

            return $counts;
        }

        if ($this->wants($type, 'weapons')) {
            $counts['weapons'] = $this->syncWeapons($id);
        }

        if ($this->wants($type, 'equipments')) {
            $counts['equipments'] = $this->syncEquipments($id);
        }

        if ($this->wants($type, 'banners')) {
            $counts['banners'] = $this->syncBanners($id);
        }

        if ($this->wants($type, 'characters')) {
            $characterCounts = $this->syncCharacters($id);
            $counts['characters'] = $characterCounts['characters'];
            $counts['attacks'] = $characterCounts['attacks'];
            $counts['abilities'] = $characterCounts['abilities'];
        }

        return $counts;
    }

    private function wants(string $type, string $name): bool
    {
        return $type === 'all' || $type === $name;
    }

    private function syncWeapons(?int $id): int
    {
        $count = 0;

        foreach ($this->details('weapons', $id) as $payload) {
            $this->syncWeapon($payload);
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function syncWeapon(array $payload): Weapon
    {
        $names = FanadataText::translations($payload, 'name');
        $weapon = $this->match(Weapon::class, (int) $payload['id'], $names['fr'] ?? null);
        $creating = ! $weapon->exists;

        $weapon->fanadata_id = (int) $payload['id'];
        $weapon->fillTranslations('name', $names);

        if ($creating) {
            $weapon->slug = $this->uniqueSlug(Weapon::class, $names['fr'] ?? $names['en'] ?? 'arme');
            $weapon->type = $this->weaponType($payload['weapon_type'] ?? null);
            $weapon->element_weapons = $this->element($payload['element_type'] ?? null);
            $weapon->start = (int) ($payload['rarity'] ?? 1);
            $weapon->hp = 0;
            $weapon->mp = 0;
            $weapon->atk = 0;
            $weapon->matk = 0;
            $weapon->def = 0;
            $weapon->mdef = 0;
            $weapon->crit = 0;
            $weapon->spd = 0;
        }

        $weapon->skills = $this->skillGroups($payload['battle_skills'] ?? []);
        $effects = $this->effects($payload['battle_skills'] ?? []);

        foreach ([1 => 'effect_1', 2 => 'effect_2', 3 => 'effect_3'] as $slot => $attribute) {
            if (isset($effects[$slot])) {
                $weapon->fillTranslations($attribute, $effects[$slot]);
            }
        }

        $characterId = $payload['character']['id'] ?? null;

        if (is_numeric($characterId)) {
            $localCharacter = Character::query()->where('fanadata_id', (int) $characterId)->first();
            if ($localCharacter) {
                $weapon->characters_id = $localCharacter->id;
            }
        }

        $weapon->save();
        $weapon->replaceStatStages($this->stages($payload['stats'] ?? []));

        return $weapon;
    }

    private function syncEquipments(?int $id): int
    {
        $count = 0;

        foreach ($this->details('equipments', $id) as $payload) {
            $this->syncEquipment($payload);
            $count++;
        }

        return $count;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function syncEquipment(array $payload): Equipment
    {
        $names = FanadataText::translations($payload, 'name');
        $equipment = $this->match(Equipment::class, (int) $payload['id'], $names['fr'] ?? null);
        $creating = ! $equipment->exists;

        $equipment->fanadata_id = (int) $payload['id'];
        $equipment->fillTranslations('name', $names);

        if ($creating) {
            $equipment->slug = $this->uniqueSlug(Equipment::class, $names['fr'] ?? $names['en'] ?? 'equipement');
            $equipment->type = $this->equipmentType($payload['equipment_type'] ?? null);
            $equipment->type_equipment = $this->element($payload['element_type'] ?? null);
            $equipment->start = (int) ($payload['rarity'] ?? 1);
            $equipment->hp = 0;
            $equipment->mp = 0;
            $equipment->atk = 0;
            $equipment->matk = 0;
            $equipment->def = 0;
            $equipment->mdef = 0;
            $equipment->crit = 0;
            $equipment->spd = 0;
        }

        $effects = $this->effects($payload['battle_skills'] ?? []);

        foreach ([1 => 'effect_1', 2 => 'effect_2'] as $slot => $attribute) {
            if (isset($effects[$slot])) {
                $equipment->fillTranslations($attribute, $effects[$slot]);
            }
        }

        $equipment->save();
        $equipment->replaceStatStages($this->stages($payload['stats'] ?? []));

        return $equipment;
    }

    private function syncBanners(?int $id): int
    {
        $count = 0;

        foreach ($this->details('banners', $id) as $payload) {
            $names = FanadataText::translations($payload, 'name');
            $banner = $this->match(Banner::class, (int) $payload['id'], $names['fr'] ?? null);
            $creating = ! $banner->exists;

            $banner->fanadata_id = (int) $payload['id'];
            $banner->fillTranslations('name', $names);

            if ($creating) {
                $banner->slug = $this->uniqueSlug(Banner::class, $names['fr'] ?? $names['en'] ?? 'banniere');
                $banner->character_ids = [];
            }

            if (isset($payload['started_at'])) {
                $banner->from = Carbon::parse($payload['started_at']);
            }

            if (isset($payload['ended_at'])) {
                $banner->to = Carbon::parse($payload['ended_at']);
            }

            if ($creating && $banner->from === null) {
                $banner->from = now();
                $banner->to = now();
            }

            $linked = $this->localCharacterIds($this->bannerCharacters($payload));

            if ($linked !== []) {
                $banner->character_ids = $linked;
            }

            $banner->save();
            $count++;
        }

        return $count;
    }

    /**
     * @return array{characters: int, attacks: int, abilities: int}
     */
    private function syncCharacters(?int $id): array
    {
        $counts = ['characters' => 0, 'attacks' => 0, 'abilities' => 0];

        foreach ($this->details('characters', $id) as $payload) {
            $this->syncCharacter($payload);
            $counts['characters']++;

            foreach ($payload['sword_skills'] ?? [] as $row) {
                if (is_array($row) && $this->syncAttack($row)) {
                    $counts['attacks']++;
                }
            }

            foreach ($payload['skill_slots'] ?? [] as $row) {
                if (is_array($row) && $this->syncAbility($row)) {
                    $counts['abilities']++;
                }
            }
        }

        return $counts;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function syncCharacter(array $payload): Character
    {
        $titles = FanadataText::translations($payload, 'name');
        $person = is_array($payload['person'] ?? null) ? $payload['person'] : [];
        $names = FanadataText::translations($person, 'name');
        $profiles = FanadataText::translations($payload, 'profile', true);

        $character = $this->matchCharacter((int) $payload['id'], $names['fr'] ?? null, $titles['fr'] ?? null);
        $creating = ! $character->exists;

        $character->fanadata_id = (int) $payload['id'];
        $character->fillTranslations('name', $names);
        $character->fillTranslations('description', $titles);
        $character->fillTranslations('profile', $profiles);

        if ($creating) {
            $character->slug = $this->uniqueSlug(Character::class, ($titles['fr'] ?? $names['fr'] ?? 'personnage'));
            $character->element = $this->element($payload['element_type'] ?? null);
            $character->weapon_type = $this->weaponType($payload['weapon_type'] ?? null);
            $character->atk1 = 0;
            $character->start = (int) ($payload['rarity'] ?? 1);
            $character->cost = 0;
            $character->hp = 0;
            $character->mp = 0;
            $character->atk = 0;
            $character->matk = 0;
            $character->def = 0;
            $character->mdef = 0;
            $character->crit = 0;
            $character->spd = 0;
            $character->ultime = $character->getTranslation('ultime', 'fr', false) ?: '';
            $character->ultime_description = $character->getTranslation('ultime_description', 'fr', false) ?: '';
            $character->image = '';
            $character->image2 = '';
        }

        foreach (['name', 'description', 'profile', 'ultime', 'ultime_description'] as $attribute) {
            if (! filled($character->getTranslation($attribute, 'fr', false))) {
                $character->setTranslation($attribute, 'fr', '');
            }
        }

        $weaponId = $payload['weapon']['id'] ?? null;

        if (is_numeric($weaponId)) {
            $weapon = Weapon::query()->where('fanadata_id', (int) $weaponId)->first();
            if ($weapon) {
                $character->weapon_id = (string) $weapon->id;
            }
        }

        $character->save();
        $character->replaceStatStages($this->stages($payload['stats'] ?? []));

        return $character;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function syncAttack(array $row): bool
    {
        $skill = $row['sword_skill'] ?? null;

        if (! is_array($skill) || ! isset($skill['id'])) {
            return false;
        }

        $names = FanadataText::translations($skill, 'name');
        $description = $this->bestSkillText($skill['texts'] ?? []);

        if ($description === []) {
            $description = $names;
        }

        if (($names['fr'] ?? $names['en'] ?? null) === null) {
            return false;
        }

        $attack = Attack::query()->where('fanadata_id', (int) $skill['id'])->first() ?? new Attack;
        $creating = ! $attack->exists;

        if ($creating) {
            $attack->slug = $this->uniqueSlug(Attack::class, $names['fr'] ?? $names['en']);
            $attack->mp_cost = (int) ($skill['mp'] ?? 0);
            $attack->type_atk = $this->attackType($names['en'] ?? $names['fr'] ?? '');
        }

        $attack->fanadata_id = (int) $skill['id'];
        $attack->fillTranslations('name', $names);
        $attack->fillTranslations('description', $description);
        $attack->save();

        return true;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function syncAbility(array $row): bool
    {
        $slot = $row['skill_slot'] ?? null;

        if (! is_array($slot) || ! isset($slot['id'])) {
            return false;
        }

        $texts = $slot['texts'] ?? [];
        $description = $this->bestLevelText($texts);

        if ($description === []) {
            return false;
        }

        $names = [];

        foreach ($description as $locale => $text) {
            $line = trim(strtok($text, "\n") ?: '');
            if ($line !== '') {
                $names[$locale] = Str::limit($line, 180, '');
            }
        }

        $ability = Ability::query()->where('fanadata_id', (int) $slot['id'])->first() ?? new Ability;
        $creating = ! $ability->exists;

        if ($creating) {
            $ability->slug = $this->uniqueSlug(Ability::class, $names['fr'] ?? $names['en'] ?? 'aptitude');
            $ability->type = 'skill';
            $ability->start = '1';
        }

        $ability->fanadata_id = (int) $slot['id'];
        $ability->fillTranslations('name', $names);
        $ability->fillTranslations('descripton', $description);
        $ability->save();

        return true;
    }

    /**
     * The list payload omits the featured characters, which only the detail route returns.
     *
     * @param  array<string, mixed>  $payload
     * @return array<int, mixed>
     */
    private function bannerCharacters(array $payload): array
    {
        if (isset($payload['characters']) && is_array($payload['characters'])) {
            return $payload['characters'];
        }

        $id = $payload['id'] ?? null;

        if (! is_numeric($id)) {
            return [];
        }

        $detail = $this->client->get('banners/'.$id);
        $characters = $detail['characters'] ?? [];

        return is_array($characters) ? $characters : [];
    }

    /**
     * @param  array<int, mixed>  $characters
     * @return list<int>
     */
    private function localCharacterIds(array $characters): array
    {
        $fanadataIds = [];

        foreach ($characters as $character) {
            if (is_array($character) && isset($character['id'])) {
                $fanadataIds[] = (int) $character['id'];
            }
        }

        if ($fanadataIds === []) {
            return [];
        }

        $local = Character::query()->whereIn('fanadata_id', $fanadataIds)->pluck('id', 'fanadata_id');
        $ids = [];

        foreach ($fanadataIds as $fanadataId) {
            $id = $local[$fanadataId] ?? null;

            if ($id) {
                $ids[] = (int) $id;
            }
        }

        return $ids;
    }

    /**
     * @return Generator<int, array<string, mixed>>
     */
    private function details(string $path, ?int $id): Generator
    {
        if ($id !== null) {
            yield $this->client->get($path.'/'.$id);

            return;
        }

        foreach ($this->client->paginate($path) as $row) {
            $itemId = $row['id'] ?? null;

            if (! is_numeric($itemId)) {
                continue;
            }

            if ($path === 'banners' && isset($row['started_at'], $row['name_fr'])) {
                yield $row;

                continue;
            }

            yield $this->client->get($path.'/'.$itemId);
        }
    }

    /**
     * @param  class-string<Model>  $model
     */
    private function match(string $model, int $fanadataId, ?string $frenchName): Model
    {
        $existing = $model::query()->where('fanadata_id', $fanadataId)->first();

        if ($existing) {
            return $existing;
        }

        $needle = FanadataText::key($frenchName);

        if ($needle === '') {
            return new $model;
        }

        $matched = $model::query()->whereNull('fanadata_id')->get()->first(function (Model $row) use ($needle): bool {
            return FanadataText::key($row->getTranslation('name', 'fr', false)) === $needle;
        });

        return $matched ?? new $model;
    }

    private function matchCharacter(int $fanadataId, ?string $person, ?string $title): Character
    {
        $existing = Character::query()->where('fanadata_id', $fanadataId)->first();

        if ($existing) {
            return $existing;
        }

        $needle = FanadataText::key($person).'|'.FanadataText::key($title);

        if ($needle === '|') {
            return new Character;
        }

        $matched = Character::query()->whereNull('fanadata_id')->get()->first(function (Character $character) use ($needle): bool {
            $key = FanadataText::key($character->getTranslation('name', 'fr', false)).'|'.FanadataText::key($character->getTranslation('description', 'fr', false));

            return $key === $needle;
        });

        return $matched ?? new Character;
    }

    public function storeWeaponSkills(): int
    {
        $count = 0;

        Weapon::query()->whereNotNull('fanadata_id')->orderBy('id')->each(function (Weapon $weapon) use (&$count): void {
            $payload = $this->client->get('weapons/'.$weapon->fanadata_id);
            $weapon->skills = $this->skillGroups($payload['battle_skills'] ?? []);
            $weapon->save();
            $count++;
        });

        return $count;
    }

    /**
     * @param  array<int, mixed>  $skills
     * @return list<array{slot: int, lines: list<array{lb: int, name: array<string, string>, text: array<string, string>}>}>
     */
    private function skillGroups(array $skills): array
    {
        $grouped = [];

        foreach ($skills as $row) {
            if (! is_array($row)) {
                continue;
            }

            $slot = (int) ($row['slot_order'] ?? 0);
            $skill = $row['battle_skill'] ?? null;

            if ($slot < 1 || ! is_array($skill)) {
                continue;
            }

            $grouped[$slot][] = [
                'lb' => (int) ($row['lb'] ?? 0),
                'name' => FanadataText::translations($skill, 'name'),
                'text' => FanadataText::translations($skill, 'text', true),
            ];
        }

        ksort($grouped);
        $groups = [];

        foreach ($grouped as $slot => $lines) {
            usort($lines, fn (array $left, array $right): int => $left['lb'] <=> $right['lb']);
            $groups[] = ['slot' => $slot, 'lines' => array_values($lines)];
        }

        return $groups;
    }

    /**
     * @param  array<int, mixed>  $skills
     * @return array<int, array<string, string>>
     */
    private function effects(array $skills): array
    {
        $bySlot = [];

        foreach ($skills as $row) {
            if (! is_array($row)) {
                continue;
            }

            $slot = (int) ($row['slot_order'] ?? 0);
            $skill = $row['battle_skill'] ?? null;

            if ($slot < 1 || ! is_array($skill)) {
                continue;
            }

            $lb = (int) ($row['lb'] ?? 0);

            if (! isset($bySlot[$slot]) || $lb < $bySlot[$slot]['lb']) {
                $bySlot[$slot] = ['lb' => $lb, 'skill' => $skill];
            }
        }

        ksort($bySlot);
        $effects = [];
        $index = 1;

        foreach ($bySlot as $slot) {
            if ($index > 3) {
                break;
            }

            $translations = FanadataText::translations($slot['skill'], 'text', true);

            if ($translations !== []) {
                $effects[$index] = $translations;
            }

            $index++;
        }

        return $effects;
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return list<array{stage: int, hp: int, mp: int, atk: int, matk: int, def: int, mdef: int, crit: int, spd: int}>
     */
    private function stages(array $rows): array
    {
        $stages = [];

        foreach ($rows as $row) {
            if (! is_array($row) || ! isset($row['lb'])) {
                continue;
            }

            $stage = ((int) $row['lb']) + 1;

            if ($stage < 1 || $stage > 5) {
                continue;
            }

            $stages[$stage] = [
                'stage' => $stage,
                'hp' => (int) ($row['max_hp'] ?? 0),
                'mp' => (int) ($row['max_mp'] ?? 0),
                'atk' => (int) ($row['max_atk'] ?? 0),
                'matk' => (int) ($row['max_matk'] ?? 0),
                'def' => (int) ($row['max_def'] ?? 0),
                'mdef' => (int) ($row['max_mdef'] ?? 0),
                'crit' => (int) ($row['max_crit'] ?? 0),
                'spd' => (int) ($row['max_spd'] ?? 0),
            ];
        }

        ksort($stages);

        return array_values($stages);
    }

    /**
     * @param  array<mixed>  $texts
     * @return array<string, string>
     */
    private function bestSkillText(array $texts): array
    {
        $best = [];
        $bestLevel = -1;

        foreach ($texts as $level => $row) {
            if (! is_array($row)) {
                continue;
            }

            $translations = FanadataText::translations($row, 'text', true);

            if ($translations === []) {
                continue;
            }

            $numeric = (int) $level;

            if ($numeric >= $bestLevel) {
                $best = $translations;
                $bestLevel = $numeric;
            }
        }

        return $best;
    }

    /**
     * @param  array<mixed>  $texts
     * @return array<string, string>
     */
    private function bestLevelText(array $texts): array
    {
        $best = [];
        $bestLevel = -1;

        foreach ($texts as $row) {
            if (! is_array($row)) {
                continue;
            }

            $translations = FanadataText::translations($row, 'text', true);

            if ($translations === []) {
                continue;
            }

            $level = (int) ($row['lv'] ?? 0);

            if ($level >= $bestLevel) {
                $best = $translations;
                $bestLevel = $level;
            }
        }

        return $best;
    }

    private function element(mixed $value): string
    {
        $key = Str::lower(trim((string) $value));

        return self::ELEMENTS[$key] ?? 'neutral';
    }

    private function weaponType(mixed $value): string
    {
        $key = Str::lower(trim((string) $value));

        return self::WEAPON_TYPES[$key] ?? 'sword';
    }

    private function equipmentType(mixed $value): string
    {
        $key = Str::lower(trim((string) $value));

        return self::EQUIPMENT_TYPES[$key] ?? 'armour';
    }

    private function attackType(string $name): AttackType
    {
        if (preg_match('/(EM|A|B|C|H|D|E)\+?$/u', $name, $matches) === 1) {
            return AttackType::from($matches[1]);
        }

        return AttackType::C;
    }

    /**
     * @param  class-string<Model>  $model
     */
    private function uniqueSlug(string $model, string $name): string
    {
        $base = Str::slug($name) ?: 'fiche';
        $slug = $base;
        $suffix = 2;

        while ($model::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacterDetailResource;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\StatsResource;
use App\Models\Character;
use App\Support\Locales;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class CharacterController extends Controller
{
    #[OA\Get(
        path: '/api/characters',
        summary: 'Get all characters',
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all characters'),
        ],
    )]
    public function index()
    {
        $page = request()->get('page', 1);
        $locale = Locales::resolve(request()->query('lang'));
        $cacheKey = 'characters_page_'.$page.'_'.$locale;

        return Cache::remember($cacheKey, 2, function () {
            return CharacterResource::collection(Character::orderBy('id', 'desc')->paginate(21));
        });
    }

    #[OA\Get(
        path: '/api/character/{character}',
        summary: 'Get a character by slug',
        tags: ['Character'],
        parameters: [
            new OA\Parameter(name: 'character', in: 'path', required: true, description: 'Character slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get a character by slug'),
            new OA\Response(response: 404, description: 'Character not found'),
        ],
    )]
    public function show(Character $character)
    {
        $character->load(['statStages', 'weapon', 'banner']);

        return CharacterDetailResource::make($character);
    }

    #[OA\Get(
        path: '/api/character/random',
        summary: 'Get a random character',
        tags: ['Character'],
        responses: [
            new OA\Response(response: 200, description: 'Get a random character'),
            new OA\Response(response: 404, description: 'Character not found'),
        ],
    )]
    public function random()
    {
        $character = Character::inRandomOrder()->first();

        if (! $character) {
            return response()->json(['message' => 'Aucun personnage trouvé'], 404);
        }

        return CharacterResource::make($character);
    }

    #[OA\Get(
        path: '/api/stats',
        summary: 'Get stats',
        tags: ['Home'],
        responses: [
            new OA\Response(response: 200, description: 'Get stats'),
        ],
    )]
    public function stats()
    {
        return StatsResource::make([]);
    }
}

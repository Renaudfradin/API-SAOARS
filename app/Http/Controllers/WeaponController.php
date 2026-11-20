<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeaponDetailResource;
use App\Http\Resources\WeaponResource;
use App\Models\Weapon;
use OpenApi\Attributes as OA;

class WeaponController extends Controller
{
    #[OA\Get(
        path: '/api/weapons',
        summary: 'Get all weapons',
        tags: ['Weapon'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all weapons'),
        ],
    )]
    public function index()
    {
        return WeaponResource::collection(Weapon::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/weapon/{weapon}',
        summary: 'Get a weapon by slug',
        tags: ['Weapon'],
        parameters: [
            new OA\Parameter(name: 'weapon', in: 'path', required: true, description: 'Weapon slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get a weapon by slug'),
            new OA\Response(response: 404, description: 'Weapon not found'),
        ],
    )]
    public function show(Weapon $weapon)
    {
        $weapon->load('statStages');

        return WeaponDetailResource::make($weapon);
    }
}

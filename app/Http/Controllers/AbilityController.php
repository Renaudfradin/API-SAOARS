<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbilityDetailResource;
use App\Http\Resources\AbilityResource;
use App\Models\Ability;
use OpenApi\Attributes as OA;

class AbilityController extends Controller
{
    #[OA\Get(
        path: '/api/abilities',
        summary: 'Get all abilities',
        tags: ['Ability'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all abilities'),
        ],
    )]
    public function index()
    {
        return AbilityResource::collection(Ability::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/ability/{ability}',
        summary: 'Get an ability by slug',
        tags: ['Ability'],
        parameters: [
            new OA\Parameter(name: 'ability', in: 'path', required: true, description: 'Ability slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get an ability by slug'),
            new OA\Response(response: 404, description: 'Ability not found'),
        ],
    )]
    public function show(Ability $ability)
    {
        return AbilityDetailResource::make($ability);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttackDetailResource;
use App\Http\Resources\AttackResource;
use App\Models\Attack;
use OpenApi\Attributes as OA;

class AttackController extends Controller
{
    #[OA\Get(
        path: '/api/attacks',
        summary: 'Get all attacks',
        tags: ['Attack'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all attacks'),
        ],
    )]
    public function index()
    {
        return AttackResource::collection(Attack::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/attack/{attack}',
        summary: 'Get an attack by slug',
        tags: ['Attack'],
        parameters: [
            new OA\Parameter(name: 'attack', in: 'path', required: true, description: 'Attack slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get an attack by slug'),
            new OA\Response(response: 404, description: 'Attack not found'),
        ],
    )]
    public function show(Attack $attack)
    {
        return AttackDetailResource::make($attack);
    }
}

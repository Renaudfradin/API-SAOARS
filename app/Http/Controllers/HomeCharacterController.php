<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeCharacterResource;
use App\Models\HomeCharacter;
use OpenApi\Attributes as OA;

class HomeCharacterController extends Controller
{
    #[OA\Get(
        path: '/api/home-characters',
        summary: 'Get home screen characters',
        tags: ['Home'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'person', in: 'query', required: false, description: 'Person key', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get home screen characters'),
        ],
    )]
    public function index()
    {
        $query = HomeCharacter::query()->orderByDesc('id');
        $person = request()->query('person');

        if (is_string($person) && $person !== '') {
            $query->where('person_key', $person);
        }

        return HomeCharacterResource::collection($query->paginate(24));
    }
}

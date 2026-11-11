<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImaginationDetailResource;
use App\Http\Resources\ImaginationResource;
use App\Models\Imagination;
use OpenApi\Attributes as OA;

class ImaginationController extends Controller
{
    #[OA\Get(
        path: '/api/imaginations',
        summary: 'Get all imaginations',
        tags: ['Imagination'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all imaginations'),
        ],
    )]
    public function index()
    {
        return ImaginationResource::collection(Imagination::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/imagination/{imagination}',
        summary: 'Get an imagination by slug',
        tags: ['Imagination'],
        parameters: [
            new OA\Parameter(name: 'imagination', in: 'path', required: true, description: 'Imagination slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get an imagination by slug'),
            new OA\Response(response: 404, description: 'Imagination not found'),
        ],
    )]
    public function show(Imagination $imagination)
    {
        return ImaginationDetailResource::make($imagination);
    }
}

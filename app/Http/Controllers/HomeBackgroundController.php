<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeBackgroundResource;
use App\Models\HomeBackground;
use OpenApi\Attributes as OA;

class HomeBackgroundController extends Controller
{
    #[OA\Get(
        path: '/api/home-backgrounds',
        summary: 'Get home screen backgrounds',
        tags: ['Home'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, description: '1 to 200. Defaults to 24.', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'world', in: 'query', required: false, description: '1 SAO, 2 ALO, 3 GGO, 4 OS, 5 UW, 6 etc.', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get home screen backgrounds'),
        ],
    )]
    public function index()
    {
        $query = HomeBackground::query()->orderByDesc('id');
        $world = request()->query('world');

        if (is_numeric($world)) {
            $query->where('world', (int) $world);
        }

        $perPage = request()->query('per_page', 24);
        $perPage = is_numeric($perPage) ? (int) $perPage : 24;
        $perPage = min(200, max(1, $perPage));

        return HomeBackgroundResource::collection($query->paginate($perPage));
    }
}

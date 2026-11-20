<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentDetailResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use OpenApi\Attributes as OA;

class EquipmentController extends Controller
{
    #[OA\Get(
        path: '/api/equipments',
        summary: 'Get all equipments',
        tags: ['Equipment'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all equipments'),
        ],
    )]
    public function index()
    {
        return EquipmentResource::collection(Equipment::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/equipment/{equipment}',
        summary: 'Get an equipment by slug',
        tags: ['Equipment'],
        parameters: [
            new OA\Parameter(name: 'equipment', in: 'path', required: true, description: 'Equipment slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get an equipment by slug'),
            new OA\Response(response: 404, description: 'Equipment not found'),
        ],
    )]
    public function show(Equipment $equipment)
    {
        $equipment->load('statStages');

        return EquipmentDetailResource::make($equipment);
    }
}

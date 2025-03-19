<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentDetailResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/equipments",
     *     summary="Get all equipments",
     *     tags={"Equipment"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all equipments"
     *     )
     * )
     */
    public function index()
    {
        return EquipmentResource::collection(Equipment::paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/api/equipments/{equipment}",
     *     summary="Get a equipment by slug",
     *     tags={"Equipment"},
     *     @OA\Parameter(
     *         name="equipment",
     *         in="path",
     *         required=true,
     *         description="Equipment slug",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a equipment by slug",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Equipment Name"),
     *             @OA\Property(property="slug", type="string", example="equipment-name"),
     *             @OA\Property(property="description", type="string", example="Equipment description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Equipment not found"
     *     )
     * )
     */
    public function show(Equipment $equipment)
    {
        return EquipmentDetailResource::make($equipment);
    }
}

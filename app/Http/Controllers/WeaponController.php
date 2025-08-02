<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeaponDetailResource;
use App\Http\Resources\WeaponResource;
use App\Models\Weapon;

class WeaponController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/weapons",
     *     summary="Get all weapons",
     *     tags={"Weapon"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get all weapons"
     *     )
     * )
     */
    public function index()
    {
        return WeaponResource::collection(Weapon::orderBy('id', 'desc')->paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/api/weapons/{weapon}",
     *     summary="Get a weapon by slug",
     *     tags={"Weapon"},
     *
     *     @OA\Parameter(
     *         name="weapon",
     *         in="path",
     *         required=true,
     *         description="Weapon slug",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get a weapon by slug",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Weapon Name"),
     *             @OA\Property(property="slug", type="string", example="weapon-name"),
     *             @OA\Property(property="description", type="string", example="Weapon description")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Weapon not found"
     *     )
     * )
     */
    public function show(Weapon $weapon)
    {
        return WeaponDetailResource::make($weapon);
    }
}

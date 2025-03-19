<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbilityDetailResource;
use App\Http\Resources\AbilityResource;
use App\Models\Ability;

class AbilityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/abilities",
     *     summary="Get all abilities",
     *     tags={"Ability"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all abilities"
     *     )
     * )
     */
    public function index()
    {
        return AbilityResource::collection(Ability::paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/api/abilities/{ability}",
     *     summary="Get a ability by slug",
     *     tags={"Ability"},
     *     @OA\Parameter(
     *         name="ability",
     *         in="path",
     *         required=true,
     *         description="Ability slug",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a ability by slug",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Ability Name"),
     *             @OA\Property(property="slug", type="string", example="ability-name"),
     *             @OA\Property(property="description", type="string", example="Ability description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ability not found"
     *     )
     * )
     */
    public function show(Ability $ability)
    {
        return AbilityDetailResource::make($ability);
    }
}

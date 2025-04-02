<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttackDetailResource;
use App\Http\Resources\AttackResource;
use App\Models\Attack;

class AttackController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/attacks",
     *     summary="Get all attacks",
     *     tags={"Attack"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get all attacks"
     *     )
     * )
     */
    public function index()
    {
        return AttackResource::collection(Attack::paginate(20));

    }

    /**
     * @OA\Get(
     *     path="/api/attack/{attack}",
     *     summary="Get a attack by slug",
     *     tags={"Attack"},
     *
     *     @OA\Parameter(
     *         name="attack",
     *         in="path",
     *         required=true,
     *         description="Attack slug",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get a attack by slug",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Attack Name"),
     *             @OA\Property(property="slug", type="string", example="attack-name"),
     *             @OA\Property(property="description", type="string", example="Attack description")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Attack not found"
     *     )
     * )
     */
    public function show(Attack $attack)
    {
        return AttackDetailResource::make($attack);
    }
}

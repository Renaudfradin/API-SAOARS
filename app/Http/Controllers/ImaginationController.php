<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImaginationDetailResource;
use App\Http\Resources\ImaginationResource;
use App\Models\Imagination;

class ImaginationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/imaginations",
     *     summary="Get all imaginations",
     *     tags={"Imagination"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all imaginations"
     *     )
     * )
     */
    public function index()
    {
        return ImaginationResource::collection(Imagination::paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/api/imaginations/{imagination}",
     *     summary="Get a imagination by slug",
     *     tags={"Imagination"},
     *     @OA\Parameter(
     *         name="imagination",
     *         in="path",
     *         required=true,
     *         description="Imagination slug",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a imagination by slug",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Imagination Name"),
     *             @OA\Property(property="slug", type="string", example="imagination-name"),
     *             @OA\Property(property="description", type="string", example="Imagination description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Imagination not found"
     *     )
     * )
     */
    public function show(Imagination $imagination)
    {
        return ImaginationDetailResource::make($imagination);
    }
}

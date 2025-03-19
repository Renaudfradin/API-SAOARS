<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacterDetailResource;
use App\Http\Resources\CharacterResource;
use App\Http\Resources\StatsResource;
use App\Models\Character;
use Illuminate\Support\Facades\Cache;

class CharacterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/characters",
     *     summary="Get all characters",
     *     tags={"Character"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all characters"
     *     )
     * )
     */
    public function index()
    {
        $page = request()->get('page', 1);
        $cacheKey = 'characters_page_' . $page;
        
        $cacheDuration = 2;
        
        return Cache::store('redis')->remember($cacheKey, $cacheDuration, function () {
            return CharacterResource::collection(Character::paginate(21));
        });
    }

    /**
     * @OA\Get(
     *     path="/api/character/{character}",
     *     summary="Get a character by slug",
     *     tags={"Character"},
     *     @OA\Parameter(
     *         name="character",
     *         in="path",
     *         required=true,
     *         description="Character slug",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a character by slug",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Character Name"),
     *             @OA\Property(property="slug", type="string", example="character-name"),
     *             @OA\Property(property="description", type="string", example="Character description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Character not found"
     *     )
     * )
     */
    public function show(Character $character)
    {
        return CharacterDetailResource::make($character);
    }

    /**
     * @OA\Get(
     *     path="/api/character/random",
     *     summary="Get a random character",
     *     tags={"Character"},
     *     @OA\Response(
     *         response=200,
     *         description="Get a random character"
     *     )
     * )
     */
    public function random()
    {
        $character = Character::inRandomOrder()->first();

        if (!$character) {
            return response()->json(['message' => 'Aucun personnage trouvé'], 404);
        }
        
        return CharacterResource::make($character);
    }

     /**
     * @OA\Get(
     *     path="/api/stats",
     *     summary="Get stats",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="Get stats"
     *     )
     * )
     */
    public function stats()
    {
        return StatsResource::make([]);
    }
}

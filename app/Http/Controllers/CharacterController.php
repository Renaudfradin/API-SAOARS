<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacterDetailResource;
use App\Http\Resources\CharacterResource;
use App\Models\Character;
use Illuminate\Support\Facades\Cache;

class CharacterController extends Controller
{
    public function index()
    {
        $page = request()->get('page', 1);
        $cacheKey = 'characters_page_' . $page;
        
        $cacheDuration = 2;
        
        return Cache::store('redis')->remember($cacheKey, $cacheDuration, function () {
            return CharacterResource::collection(Character::paginate(21));
        });
    }

    public function show(Character $character)
    {
        return CharacterDetailResource::make($character);
    }

    public function random()
    {
        $character = Character::inRandomOrder()->first();

        if (!$character) {
            return response()->json(['message' => 'Aucun personnage trouvé'], 404);
        }
        
        return CharacterResource::make($character);
    }
}

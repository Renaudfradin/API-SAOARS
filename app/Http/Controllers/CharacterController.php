<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacterDetailResource;
use App\Http\Resources\CharacterResource;
use App\Models\Character;

class CharacterController extends Controller
{
    public function index()
    {
        return CharacterResource::collection(Character::paginate(21));
    }

    public function show(Character $character)
    {
        return CharacterDetailResource::make($character);
    }
}

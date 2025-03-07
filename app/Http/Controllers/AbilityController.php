<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbilityDetailResource;
use App\Http\Resources\AbilityResource;
use App\Models\Ability;

class AbilityController extends Controller
{
    public function index()
    {
        return AbilityResource::collection(Ability::paginate(20));
    }

    public function show(Ability $ability)
    {
        return AbilityDetailResource::make($ability);
    }
}

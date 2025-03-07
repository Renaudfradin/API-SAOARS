<?php

namespace App\Http\Controllers;

use App\Http\Resources\AttackDetailResource;
use App\Http\Resources\AttackResource;
use App\Models\Attack;

class AttackController extends Controller
{
    public function index()
    {
        return AttackResource::collection(Attack::paginate(20));

    }

    public function show(Attack $attack)
    {
        return AttackDetailResource::make($attack);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\WeaponDetailResource;
use App\Http\Resources\WeaponResource;
use App\Models\Weapon;

class WeaponController extends Controller
{
    public function index()
    {
        return WeaponResource::collection(Weapon::paginate(20));
    }

    public function show(Weapon $weapon)
    {
        return WeaponDetailResource::make($weapon);
    }
}

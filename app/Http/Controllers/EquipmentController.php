<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentDetailResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        return EquipmentResource::collection(Equipment::paginate(20));
    }

    public function show(Equipment $equipment)
    {
        return EquipmentDetailResource::make($equipment);
    }
}

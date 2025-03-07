<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImaginationDetailResource;
use App\Http\Resources\ImaginationResource;
use App\Models\Imagination;

class ImaginationController extends Controller
{
    public function index()
    {
        return ImaginationResource::collection(Imagination::paginate(20));
    }

    public function show(Imagination $imagination)
    {
        return ImaginationDetailResource::make($imagination);
    }
}

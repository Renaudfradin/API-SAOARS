<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        return BannerResource::collection(Banner::paginate(20));
    }

    public function show(Banner $banner)
    {
        return BannerDetailResource::make($banner);
    }
}

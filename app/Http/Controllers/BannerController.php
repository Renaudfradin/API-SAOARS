<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use OpenApi\Attributes as OA;

class BannerController extends Controller
{
    #[OA\Get(
        path: '/api/banners',
        summary: 'Get all banners',
        tags: ['Banner'],
        parameters: [
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get all banners'),
        ],
    )]
    public function index()
    {
        return BannerResource::collection(Banner::orderBy('id', 'desc')->paginate(20));
    }

    #[OA\Get(
        path: '/api/banner/{banner}',
        summary: 'Get a banner by slug',
        tags: ['Banner'],
        parameters: [
            new OA\Parameter(name: 'banner', in: 'path', required: true, description: 'Banner slug', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'lang', in: 'query', required: false, description: 'Locale: fr, en, zh, ko, ja, de, es. Defaults to fr.', schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Get a banner by slug'),
            new OA\Response(response: 404, description: 'Banner not found'),
        ],
    )]
    public function show(Banner $banner)
    {
        return BannerDetailResource::make($banner);
    }
}

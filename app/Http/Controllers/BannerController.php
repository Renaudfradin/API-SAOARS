<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/banners",
     *     summary="Get all banners",
     *     tags={"Banner"},
     *     @OA\Response(
     *         response=200,
     *         description="Get all banners"
     *     )
     * )
     */
    public function index()
    {
        return BannerResource::collection(Banner::paginate(20));
    }

    /**
     * @OA\Get(
     *     path="/api/banner/{banner}",
     *     summary="Get a banner by slug",
     *     tags={"Banner"},
     *     @OA\Parameter(
     *         name="banner",
     *         in="path",
     *         required=true,
     *         description="Banner slug",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get a banner by slug",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Banner Name"),
     *             @OA\Property(property="slug", type="string", example="banner-name"),
     *             @OA\Property(property="description", type="string", example="Banner description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Banner not found"
     *     )
     * )
     */
    public function show(Banner $banner)
    {
        return BannerDetailResource::make($banner);
    }
}

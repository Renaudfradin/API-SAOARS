<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/",
     *     summary="Default Home",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="Default Home"
     *     )
     * )
     */

    public function index()
    {
        return response()->json([
            'message' => "Bonjour bienvenue sur l'api SAOARS/UB développer par Renaud Fradin https://github.com/Renaudfradin, Crédits des données/images relatives au jeu Sword Art Online Alicization Rising Steel/Unleash Blading à Bandai Namco Entertainment Inc. et autres auteurs respectifs.",
        ], 200);
    }
}

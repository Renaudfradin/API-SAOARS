<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/characters', [CharacterController::class, 'index']);

Route::get('/character/{character:slug}', [CharacterController::class, 'show']);
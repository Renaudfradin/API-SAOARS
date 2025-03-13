<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\AttackController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImaginationController;
use App\Http\Controllers\WeaponController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/characters', [CharacterController::class, 'index']);

Route::get('/character/random', [CharacterController::class, 'random']);

Route::get('/character/{character:slug}', [CharacterController::class, 'show']);

Route::get('/banners', [BannerController::class, 'index']);

Route::get('/banner/{banner:slug}', [BannerController::class, 'show']);

Route::get('/attacks', [AttackController::class, 'index']);

Route::get('/attack/{attack:slug}', [AttackController::class, 'show']);

Route::get('/abilities', [AbilityController::class, 'index']);

Route::get('/ability/{ability:slug}', [AbilityController::class, 'show']);

Route::get('/equipments', [EquipmentController::class, 'index']);

Route::get('/equipment/{equipment:slug}', [EquipmentController::class, 'show']);

Route::get('/imaginations', [ImaginationController::class, 'index']);

Route::get('/imagination/{imagination:slug}', [ImaginationController::class, 'show']);

Route::get('/weapons', [WeaponController::class, 'index']);

Route::get('/weapon/{weapon:slug}', [WeaponController::class, 'show']);

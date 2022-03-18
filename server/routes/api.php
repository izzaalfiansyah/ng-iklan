<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user/login', [Api\UserController::class, 'login']);

Route::resources([
    '/iklan/kategori' => Api\IklanKategoriController::class,
    '/iklan/request' => Api\IklanRequestController::class,
    '/iklan' => Api\IklanController::class,
    '/user' => Api\UserController::class,
]);
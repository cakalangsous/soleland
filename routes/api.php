<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\KidAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::prefix('kid')->group(function() {
    Route::post('/register', [KidAuthController::class, 'register'])->middleware(['auth:sanctum', 'auth:parent', 'type.parent']);
    Route::post('/login', [KidAuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'auth:character', 'type.character'])->group(function() {
        Route::get('profile', [KidAuthController::class, 'profile']);
        
        Route::post('/update', [CharacterController::class, 'update']);
    });
});
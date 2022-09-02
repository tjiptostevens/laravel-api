<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
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

// Public routes
Route::get('/menus', [MenuController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);

//Protected routes-Only authenticated users can have access to protected routes//
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Menu
    Route::post('/menus', [MenuController::class, 'store']);
    Route::put('/menus/{id}', [MenuController::class, 'update']);
    Route::delete('/menus/{id}', [MenuController::class, 'delete']);
});

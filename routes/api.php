<?php

use App\Http\Controllers\LevelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});

Route::get('/test', function () {
    return auth()->user();
})->middleware('auth:sanctum');
//rutas backend,a estas rutas se hacen las solicitudes desde el front
Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('profile', [AuthController::class, 'profile']);
Route::get('getAllUsers', [AuthController::class, 'getAllUsers']);
Route::post('editUser/{id}', [AuthController::class, 'editUser']);
Route::delete('deleteUser/{id}', [AuthController::class, 'deleteUser']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::group(
    ['middleware' => 'api'],
    function ($router) {


        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

    }
);

//Rutas Aldrick

Route::get('/level', [LevelController::class,'index']);



<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;

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
Route::post('/auth/register',[UserController::class ,'createUser']);
Route::post('/auth/login',[UserController::class ,'loginUser']);

Route::group(['middleware'=>['auth:sanctum']],function () {
    Route::get('/auth/profile',[UserController::class ,'profile']);
    Route::delete('/auth/logout',[UserController::class ,'logout']);
    Route::post('/auth/update-score',[UserController::class ,'updateScore']);
});
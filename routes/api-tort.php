<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\AppController;

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

Route::post('login', [AuthController::class, 'login']);

Route::group(["middleware" => ['auth:sanctum', 'reqAuth']], function () {
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::group(["middleware" => ['auth:sanctum', 'reqAuth','adminOnly']], function () {
    Route::get('user', [AppController::class, 'getAllUsers']);
    Route::post('user', [AuthController::class, 'registration']);
    Route::post('work-shift', [AppController::class, 'createSchema']); // Создание смены
    Route::get('work-shift/{smenaId}/open', [AppController::class, 'openSmena']); // Открытие смены
    Route::get('work-shift/{smenaId}/close', [AppController::class, 'closeSmena']); // Закрытие смены
    Route::post('work-shift/{smenaId}/user', [AppController::class, 'addEmployeeOnSmena']); // Закрытие смены
    Route::get('work-shift/{smenaId}/order', [AppController::class, 'getOrderBySmenaId']); // Закрытие смены



});

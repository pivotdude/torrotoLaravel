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
    Route::post('work-shift', [AppController::class, 'createSchema']);
    Route::get('work-shift/{smenaId}/open', [AppController::class, 'openSmena']);
    Route::get('work-shift/{smenaId}/close', [AppController::class, 'closeSmena']);
    Route::post('work-shift/{smenaId}/user', [AppController::class, 'addEmployeeOnSmena']);
    Route::get('work-shift/{smenaId}/order', [AppController::class, 'getOrderBySmenaId']);
});
Route::group(["middleware" => ['auth:sanctum', 'reqAuth','walterOnly']], function () {
    Route::post('order', [AppController::class, 'addOrder']);
    Route::get('order/{orderId}', [AppController::class, 'getOrderById']);
    Route::get('work-shift/{smenaId}/orders', [AppController::class, 'getOrders']);
});
Route::group(["middleware" => ['auth:sanctum', 'reqAuth', 'cookOnly']], function () {
    Route::patch('order/{orderId}/change-status', [AppController::class, 'changeStatus']);
    Route::get('order/taken', [AppController::class, 'getOrdersActiveSmena']);
});

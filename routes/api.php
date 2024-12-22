<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientsController;
use App\Http\Controllers\Api\BookingsController;
use App\Http\Controllers\Api\JournalsController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientsController::class, 'index']);
    Route::post('/clients', [ClientsController::class, 'store']);
    Route::delete('/clients/{id}', [ClientsController::class, 'destroy']);
    Route::delete('/bookings/{id}', [BookingsController::class, 'destroy']);

    Route::get('/clients/{client}/journals', [JournalsController::class, 'index']);
    Route::post('/clients/{client}/journals', [JournalsController::class, 'store']);
    Route::delete('/clients/{client}/journals/{id}', [JournalsController::class, 'destroy']);

});

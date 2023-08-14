<?php

use App\Http\Controllers\Api\Master\PenyewaController;
use App\Http\Controllers\Api\Master\SopirController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'master'],function(){
    Route::group(['prefix' =>"penyewa"],function(){
        Route::get("/", [PenyewaController::class, "index"]);
        Route::post("/", [PenyewaController::class, "store"]);
        Route::get("/{id}", [PenyewaController::class, "show"]);
        Route::put("/{id}", [PenyewaController::class, "update"]);
        Route::delete("/{id}", [PenyewaController::class, "destroy"]);
    });
    Route::group(['prefix' =>"sopir"],function(){
        Route::get("/", [SopirController::class, "index"]);
        Route::post("/", [SopirController::class, "store"]);
        Route::get("/{id}", [SopirController::class, "show"]);
        Route::put("/{id}", [SopirController::class, "update"]);
        Route::delete("/{id}", [SopirController::class, "destroy"]);
    });
});
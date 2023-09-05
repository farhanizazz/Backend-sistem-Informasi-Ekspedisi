<?php

use App\Http\Controllers\Api\Master\ArmadaController;
use App\Http\Controllers\Api\Master\PenyewaController;
use App\Http\Controllers\Api\Master\RekeningController;
use App\Http\Controllers\Api\Master\RoleController;
use App\Http\Controllers\Api\Master\SopirController;
use App\Http\Controllers\Api\Master\UserController;
use App\Http\Controllers\Api\Master\SubkonController;
use App\Http\Controllers\Api\Transaksi\HutangSopirController;
use App\Http\Controllers\Api\Transaksi\PengeluaranContoller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\RegisterController;
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

Route::post("/login",   [AuthController::class, "login"]);
Route::post("/register", [UserController::class, "store"]);

Route::middleware('jwt.verify')->group(function () {
    Route::get("/getProfile", [AuthController::class, "userProfile"]);
    // Route Master
    Route::group(['prefix' => 'master'], function () {
        Route::group(['prefix' => "penyewa"], function () {
            Route::get("/", [PenyewaController::class, "index"]);
            Route::post("/", [PenyewaController::class, "store"]);
            Route::get("/{id}", [PenyewaController::class, "show"]);
            Route::put("/{id}", [PenyewaController::class, "update"]);
            Route::delete("/{id}", [PenyewaController::class, "destroy"]);
        });
        Route::group(['prefix' => "user"], function () {
            Route::get("/", [UserController::class, "index"]);
        });
        Route::group(['prefix' => "sopir"], function () {
            Route::get("/", [SopirController::class, "index"]);
            Route::post("/", [SopirController::class, "store"]);
            Route::get("/{id}", [SopirController::class, "show"]);
            Route::put("/{id}", [SopirController::class, "update"]);
            Route::delete("/{id}", [SopirController::class, "destroy"]);
        });
        Route::group(['prefix' => "armada"], function () {
            Route::get("/", [ArmadaController::class, "index"]);
            Route::post("/", [ArmadaController::class, "store"]);
            Route::get("/{id}", [ArmadaController::class, "show"]);
            Route::put("/{id}", [ArmadaController::class, "update"]);
            Route::delete("/{id}", [ArmadaController::class, "destroy"]);
        });
        Route::group(['prefix' => "rekening"], function () {
            Route::get("/", [RekeningController::class, "index"]);
            Route::get("/total", [RekeningController::class, "total"]);
            Route::get("/{id}", [RekeningController::class, "show"]);
            Route::post("/", [RekeningController::class, "store"]);
            Route::put("/{id}", [RekeningController::class, "update"]);
            Route::delete("/{id}", [RekeningController::class, "destroy"]);
        });
        Route::group(['prefix' => "subkon"], function () {
            Route::get("/", [SubkonController::class, "index"]);
            Route::post("/", [SubkonController::class, "store"]);
            Route::get("/{id}", [SubkonController::class, "show"]);
            Route::put("/{id}", [SubkonController::class, "update"]);
            Route::delete("/{id}", [SubkonController::class, "destroy"]);
        });

        Route::group(["prefix" => "roles"], function () {
            Route::get("/", [RoleController::class, "index"]);
            Route::post("/", [RoleController::class, "store"]);
            Route::get("/{id}", [RoleController::class, "show"]);
            Route::put("/{id}", [RoleController::class, "update"]);
            Route::delete("/{id}", [RoleController::class, "destroy"]);
        });
    });
    Route::group(['prefix' => "sopir"], function () {
        Route::get("/", [SopirController::class, "index"]);
        Route::post("/", [SopirController::class, "store"]);
        Route::get("/{id}", [SopirController::class, "show"]);
        Route::put("/{id}", [SopirController::class, "update"]);
        Route::delete("/{id}", [SopirController::class, "destroy"]);
    });
    Route::group(['prefix' => "armada"], function () {
        Route::get("/", [ArmadaController::class, "index"]);
        Route::post("/", [ArmadaController::class, "store"]);
        Route::get("/{id}", [ArmadaController::class, "show"]);
        Route::put("/{id}", [ArmadaController::class, "update"]);
        Route::delete("/{id}", [ArmadaController::class, "destroy"]);
    });
    Route::group(['prefix' => "rekening"], function () {
        Route::get("/", [RekeningController::class, "index"]);
        Route::get("/total", [RekeningController::class, "total"]);
        Route::get("/{id}", [RekeningController::class, "show"]);
        Route::post("/", [RekeningController::class, "store"]);
        Route::put("/{id}", [RekeningController::class, "update"]);
        Route::delete("/{id}", [RekeningController::class, "destroy"]);
    });
    Route::group(['prefix' => "subkon"], function () {
        Route::get("/", [SubkonController::class, "index"]);
        Route::post("/", [SubkonController::class, "store"]);
        Route::get("/{id}", [SubkonController::class, "show"]);
        Route::put("/{id}", [SubkonController::class, "update"]);
        Route::delete("/{id}", [SubkonController::class, "destroy"]);
    });

    Route::group(["prefix" => "roles"], function () {
        Route::get("/", [RoleController::class, "index"]);
        Route::post("/", [RoleController::class, "store"]);
        Route::get("/{id}", [RoleController::class, "show"]);
        Route::put("/{id}", [RoleController::class, "update"]);
        Route::delete("/{id}", [RoleController::class, "destroy"]);
    });
});
Route::group(['prefix' => 'transaksi'], function () {
    Route::group(['prefix' => 'hutang_sopir'], function () {
        Route::get("/", [HutangSopirController::class, "index"]);
        Route::post("/", [HutangSopirController::class, "store"]);
        Route::get("/{id}", [HutangSopirController::class, "show"]);
        Route::put("/{id}", [HutangSopirController::class, "update"]);
        Route::delete("/{id}", [HutangSopirController::class, "destroy"]);
    });
});

Route::group(['prefix' => 'notifikasi'], function () {
    Route::get("/getReminderPajak", [NotifikasiController::class, "getReminderPajak"]);
});

Route::group(['prefix' => 'pengeluaran'], function () {
    Route::get("/", [PengeluaranContoller::class, "index"]);
    Route::post("/", [PengeluaranContoller::class, "store"]);
    Route::get("/{id}", [PengeluaranContoller::class, "show"]);
    Route::put("/{id}", [PengeluaranContoller::class, "update"]);
    Route::delete("/{id}", [PengeluaranContoller::class, "destroy"]);
});
// });

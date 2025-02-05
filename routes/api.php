<?php

use App\Http\Controllers\Api\Laporan\LaporanPemasukanCVController;
use App\Http\Controllers\Api\Laporan\LaporanPemasukanKendaraanController;
use App\Http\Controllers\Api\Laporan\LaporanPengeluaranController;
use App\Http\Controllers\Api\Laporan\LaporanV2Controller;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\Master\ArmadaController;
use App\Http\Controllers\Api\Master\PenyewaController;
use App\Http\Controllers\Api\Master\RekeningController;
use App\Http\Controllers\Api\Master\RoleController;
use App\Http\Controllers\Api\Master\SopirController;
use App\Http\Controllers\Api\Master\UserController;
use App\Http\Controllers\Api\Master\SubkonController;
use App\Http\Controllers\Api\Transaksi\HutangSopirController;
use App\Http\Controllers\Api\Transaksi\OrderController;
use App\Http\Controllers\Api\Transaksi\PengeluaranContoller;
use App\Http\Controllers\Api\Master\MutasiController;
use App\Http\Controllers\Api\Master\TambahanController;
use App\Http\Controllers\Api\Transaksi\LainLainController;
use App\Http\Controllers\Api\Transaksi\NotaBeliController;
use App\Http\Controllers\Api\Transaksi\ServisController;
use App\Http\Controllers\Api\Transaksi\TagihanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotifikasiController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login",   [AuthController::class, "login"]);
Route::post("/register", [UserController::class, "store"]);

Route::middleware('jwt.verify')->group(function () {
    Route::get("/getProfile", [AuthController::class, "userProfile"]);
    Route::post('update-password', [AuthController::class, 'updatePassword']);
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
            Route::get("/{id}", [UserController::class, "show"]);
            Route::put("/{id}", [UserController::class, "update"]);
            Route::delete("/{id}", [UserController::class, "destroy"]);
            Route::post("/", [UserController::class, "create"]);
        });
        Route::group(['prefix' => "sopir"], function () {
            Route::get("/", [SopirController::class, "index"]);
            Route::get("/paginate", [SopirController::class, "paginate"]);
            Route::post("/", [SopirController::class, "store"]);
            Route::get("/{id}", [SopirController::class, "show"]);
            Route::put("/{id}", [SopirController::class, "update"]);
            Route::delete("/{id}", [SopirController::class, "destroy"]);
        });
        Route::group(['prefix' => "tambahan"], function () {
            Route::get("/", [TambahanController::class, "index"]);
            Route::post("/", [TambahanController::class, "store"]);
            Route::get("/{id}", [TambahanController::class, "show"]);
            Route::put("/{id}", [TambahanController::class, "update"]);
            Route::delete("/{id}", [TambahanController::class, "destroy"]);
        });
        Route::group(['prefix' => "armada"], function () {
            Route::get("/", [ArmadaController::class, "index"]);
            Route::post("/", [ArmadaController::class, "store"]);
            Route::get("/{id}", [ArmadaController::class, "show"]);
            Route::put("/{id}", [ArmadaController::class, "update"]);
            Route::delete("/{id}", [ArmadaController::class, "destroy"]);
        });
        Route::group(['prefix' => "rekening"], function () {
            Route::get("/{rekening_id}/mutasi", [MutasiController::class, "getMutasiByRekening"]);
            Route::group(['prefix' => "mutasi"], function () {
                Route::get("/", [MutasiController::class, "index"]);
                Route::post("/", [MutasiController::class, "store"]);
                Route::get("/{id}", [MutasiController::class, "show"]);
                Route::put("/{id}", [MutasiController::class, "update"]);
                Route::delete("/{id}", [MutasiController::class, "destroy"]);
            });
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
        
        Route ::group(["prefix" => "laporan/servis"], function () {
            Route::get("/", [ServisController::class, "index"]);
            Route::post("/", [ServisController::class, "store"]);
            Route::get("/{id}", [ServisController::class, "show"]);
            Route::put("/{id}", [ServisController::class, "update"]);
            Route::delete("/{id}", [ServisController::class, "destroy"]);
        
            Route::group(["prefix" => "mutasi"], function(){
                Route::post("/", [ServisController::class, "createServisMutasi"]);
                Route::delete("/{id}", [ServisController::class, "deleteServisMutasi"]);
            });
        });
        Route::group(["prefix" => "laporan/lainlain"], function () {
            Route::get("/", [LainLainController::class, "index"]);
            Route::post("/", [LainLainController::class, "store"]);
            Route::get("/{id}", [LainLainController::class, "show"]);
            Route::put("/{id}", [LainLainController::class, "update"]);
            Route::delete("/{id}", [LainLainController::class, "destroy"]);
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
    // });
    Route::group(['prefix' => 'transaksi'], function () {
        Route::group(['prefix' => 'hutang-sopir'], function () {
            Route::get("/total", [HutangSopirController::class, "total"]);
            Route::get("/total/{id}", [HutangSopirController::class, "totalById"]);
            Route::get("/", [HutangSopirController::class, "index"]);
            Route::post("/", [HutangSopirController::class, "store"]);
            Route::get("/{id}", [HutangSopirController::class, "show"]);
            Route::get("/{id}/list", [HutangSopirController::class, "getListHutangSopirById"]);
            Route::put("/{id}", [HutangSopirController::class, "update"]);
            Route::delete("/{id}", [HutangSopirController::class, "destroy"]);
        });
    
        Route::group(['prefix' => 'order'], function () {
            Route::get("/", [OrderController::class, "index"]);
            Route::post("/", [OrderController::class, "store"]);
            Route::get("/{id}", [OrderController::class, "show"]);
            Route::put("/{id}", [OrderController::class, "update"]);
            Route::delete("/{id}", [OrderController::class, "destroy"]);
        });
        Route::group(["prefix" => "laporan/servis"], function () {
            Route::get("/", [ServisController::class, "index"]);
            Route::post("/", [ServisController::class, "store"]);
            Route::get("/{id}", [ServisController::class, "show"]);
            Route::put("/{id}", [ServisController::class, "update"]);
            Route::delete("/{id}", [ServisController::class, "destroy"]);
        });
        Route ::group(["prefix" => "laporan/nota-beli"], function () {
            Route::get("/", [NotaBeliController::class, "index"]);
            Route::post("/", [NotaBeliController::class, "store"]);
            Route::get("/{id}", [NotaBeliController::class, "show"]);
            Route::put("/{id}", [NotaBeliController::class, "update"]);
            Route::delete("/{id}", [NotaBeliController::class, "destroy"]);
        });
    
        Route::group(["prefix" => "laporan/invoice"], function(){
            Route::get("/", [TagihanController::class, "index"]);
            Route::post("/", [TagihanController::class, "create"]);
            Route::delete("/{id}", [TagihanController::class, "destroy"]);
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

    Route::post("/logout", [AuthController::class, "logout"]);
});

Route::get("/log",[LogController::class, "index"]);

Route::group(['prefix' => 'laporan'], function (){
    Route::get("/pemasukan-cv", [LaporanPemasukanCVController::class, "getLaporanPemasukanCV"]);
    Route::get("/pemasukan-kendaraan-sendiri", [LaporanPemasukanKendaraanController::class, "getLaporanPemasukanKendaraanSendiri"]);
    Route::get("/pemasukan-kendaraan-subkon", [LaporanPemasukanKendaraanController::class, "getLaporanPemasukanKendaraanSubkon"]);
    Route::get("/pengeluaran-servis", [LaporanPengeluaranController::class, "getLaporanPengeluaranServis"]);
    Route::get("/pengeluaran-lain", [LaporanPengeluaranController::class, "getLaporanPengeluaranLain"]);
    Route::get("/pengeluaran-semua", [LaporanPengeluaranController::class, "getLaporanPengeluaranSemua"]);
});


Route::prefix('laporan-v2')->group(function() {
    Route::get('hutang-sopir', [LaporanV2Controller::class, 'hutangSopir']);
    Route::get('hutang-customer', [LaporanV2Controller::class, 'hutangPiutangCustomer']);
    Route::get('hutang-subkon', [LaporanV2Controller::class, 'hutangPiutangSubkon']);
    Route::get('kas-harian', [LaporanV2Controller::class, 'kasHarian']);
});

// other route force to not found
Route::any('{any}', function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Not Found'
    ], 404);
})->where('any', '.*');

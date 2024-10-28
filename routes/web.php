<?php

use App\Http\Controllers\Api\Laporan\LaporanPemasukanCVController;
use App\Http\Controllers\Api\Laporan\LaporanPemasukanKendaraanController;
use App\Http\Controllers\Api\Laporan\LaporanPengeluaranController;
use App\Http\Controllers\Api\Transaksi\TagihanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the application"
 * )
 */

Route::get('/', function () {
    return view('welcome');
});

Route::get('/transaksi/laporan/invoice/{id}/export',[TagihanController::class, 'generateWORD']);

Route::group(['prefix'=> 'export-pdf'], function () {
    Route::get('export-pdf/transaksi/laporan/pemasukan-cv', [LaporanPemasukanCVController::class, 'generatePemasukanCVPDF']);
    Route::get('export-pdf/transaksi/laporan/pemasukan-kendaraan-subkon', [LaporanPemasukanKendaraanController::class, 'generatePemasukanKendaraanSubkonPDF']);
    Route::get('export-pdf/transaksi/laporan/pemasukan-kendaraan-sendiri', [LaporanPemasukanKendaraanController::class, 'generatePemasukanKendaraanSendiriPDF']);
    Route::get('export-pdf/transaksi/laporan/pengeluaran-servis', [LaporanPengeluaranController::class, 'generatePengeluaranServisPDF']);
    Route::get('export-pdf/transaksi/laporan/pengeluaran-lain', [LaporanPengeluaranController::class, 'generatePengeluaranLainPDF']);
    Route::get('export-pdf/transaksi/laporan/pengeluaran-semua', [LaporanPengeluaranController::class, 'generatePengeluaranSemuaPDF']);
});


Route::group(['prefix'=> 'export-word'], function () {
    Route::get('/transaksi/laporan/pengeluaran-servis', [LaporanPengeluaranController::class, 'generatePengeluaranServisWORD']);
    Route::get('/transaksi/laporan/pengeluaran-lain', [LaporanPengeluaranController::class, 'generatePengeluaranLainWORD']);
    Route::get('/transaksi/laporan/pengeluaran-semua', [LaporanPengeluaranController::class, 'generatePengeluaranSemuaWORD']);
});




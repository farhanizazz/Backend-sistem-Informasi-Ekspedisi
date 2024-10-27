<?php

use App\Http\Controllers\Api\Laporan\LaporanPemasukanCVController;
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

Route::get('/transaksi/laporan/pemasukan-cv', [LaporanPemasukanCVController::class, 'generatePemasukanCVPDF']);

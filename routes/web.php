<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataMaster;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => view('dashboard'));

Route::controller(DataMaster::class)->group(function () {
    Route::get('/prodi', 'prodi')->name('prodi.index');
    Route::get('/prodi/ajax', 'ajaxProdi')->name('prodi.ajax');
    Route::post('/prodi/store','storeProdi')->name('prodi.store');
    Route::get('/tahun', 'tahun')->name('tahun.index');
    Route::get('/tahun/ajax', 'ajaxTahun')->name('tahun.ajax');
    Route::post('/prodi/store', [ProdiController::class, 'store'])->name('prodi.store');

});
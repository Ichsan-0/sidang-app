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
    // Prodi CRUD
    Route::get('/prodi', 'prodi')->name('prodi.index');
    Route::get('/prodi/ajax', 'ajaxProdi')->name('prodi.ajax');
    Route::post('/prodi/store', 'storeProdi')->name('prodi.store');
    Route::get('/prodi/edit/{id}', 'editProdi')->name('prodi.edit');
    Route::post('/prodi/update/{id}', 'updateProdi')->name('prodi.update');
    Route::delete('/prodi/delete/{id}', 'deleteProdi')->name('prodi.delete');

    Route::get('/tahun', 'tahun')->name('tahun.index');
    Route::get('/tahun/ajax', 'ajaxTahun')->name('tahun.ajax');
    // Fakultas CRUD
    Route::get('/fakultas', 'fakultas')->name('fakultas.index');
    Route::get('/fakultas/ajax', 'ajaxFakultas')->name('fakultas.ajax');
    Route::post('/fakultas/store', 'storeFakultas')->name('fakultas.store');
    Route::get('/fakultas/edit/{id}', 'editFakultas')->name('fakultas.edit');
    Route::post('/fakultas/update/{id}', 'updateFakultas')->name('fakultas.update');
    Route::delete('/fakultas/delete/{id}', 'deleteFakultas')->name('fakultas.delete');

});
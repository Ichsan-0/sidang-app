<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataMaster;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DataMaster\MahasiswaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-prodi', [DataMaster::class, 'getProdi']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('users', UserController::class);

    Route::controller(DataMaster::class)->group(function () {  // Prodi CRUD
        Route::get('/tahun', 'tahun')->name('tahun.index');
        Route::get('/tahun/ajax', 'ajaxTahun')->name('tahun.ajax');
        // Prodi CRUD
        Route::get('/prodi', 'prodi')->name('prodi.index');
        Route::get('/prodi/ajax', 'ajaxProdi')->name('prodi.ajax');
        Route::post('/prodi/store', 'storeProdi')->name('prodi.store');
        Route::get('/prodi/edit/{id}', 'editProdi')->name('prodi.edit');
        Route::post('/prodi/update/{id}', 'updateProdi')->name('prodi.update');
        Route::delete('/prodi/delete/{id}', 'deleteProdi')->name('prodi.delete');
        // Fakultas CRUD
        Route::get('/fakultas', 'fakultas')->name('fakultas.index');
        Route::get('/fakultas/ajax', 'ajaxFakultas')->name('fakultas.ajax');
        Route::post('/fakultas/store', 'storeFakultas')->name('fakultas.store');
        Route::get('/fakultas/edit/{id}', 'editFakultas')->name('fakultas.edit');
        Route::post('/fakultas/update/{id}', 'updateFakultas')->name('fakultas.update');
        Route::delete('/fakultas/delete/{id}', 'deleteFakultas')->name('fakultas.delete');
        // Pimpinan CRUD
        Route::get('/pimpinan', 'pimpinan')->name('pimpinan.index');
        Route::get('/pimpinan/ajax', 'ajaxPimpinan')->name('pimpinan.ajax');
        Route::post('/pimpinan/store', 'storePimpinan')->name('pimpinan.store');
        Route::get('/pimpinan/edit/{id}', 'editPimpinan')->name('pimpinan.edit');
        Route::post('/pimpinan/update/{id}', 'updatePimpinan')->name('pimpinan.update');
        Route::delete('/pimpinan/delete/{id}', 'deletePimpinan')->name('pimpinan.delete');

        Route::get('/pengajuan-judul', 'pengajuanJudul')->name('pengajuan-judul.index');
    });

    Route::resource('mahasiswa', MahasiswaController::class);

    // Tambahkan route lain yang ada di sidebar jika perlu
});
    // Tambahkan route lain yang ada di sidebar jika perlu
});


require __DIR__.'/auth.php';

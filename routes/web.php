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

        Route::controller(DataMaster::class)->group(function () {  
            // Tahun CRUD
            Route::get('/tahun', 'tahun')->name('tahun.index');
            Route::get('/tahun/ajax', 'ajaxTahun')->name('tahun.ajax');
            Route::post('/tahun/store', 'storeTahun')->name('tahun.store');
            Route::get('/tahun/edit/{id}', 'editTahun')->name('tahun.edit');
            Route::post('/tahun/update/{id}', 'updateTahun')->name('tahun.update');
            Route::delete('/tahun/delete/{id}', 'deleteTahun')->name('tahun.delete');
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

            Route::get('/jenis-penelitian', 'jenisPenelitian')->name('jenis-penelitian.index');
            Route::get('/jenis-penelitian/ajax', 'ajaxJenisPenelitian')->name('jenis-penelitian.ajax');
            Route::post('/jenis-penelitian/store', 'storeJenisPenelitian')->name('jenis-penelitian.store');
            Route::get('/jenis-penelitian/edit/{id}', 'editJenisPenelitian')->name('jenis-penelitian.edit');
            Route::post('/jenis-penelitian/update/{id}', 'updateJenisPenelitian')->name('jenis-penelitian.update');

            Route::get('/bidang-peminatan', 'bidangPeminatan')->name('bidang-peminatan.index');
            Route::get('/bidang-peminatan/ajax', 'ajaxBidangPeminatan')->name('bidang-peminatan.ajax');
            Route::post('/bidang-peminatan/store', 'storeBidangPeminatan')->name('bidang-peminatan.store');
            Route::get('/bidang-peminatan/edit/{id}', 'editBidangPeminatan')->name('bidang-peminatan.edit');
            Route::post('/bidang-peminatan/update/{id}', 'updateBidangPeminatan')->name('bidang-peminatan.update');
            Route::delete('/bidang-peminatan/delete/{id}', 'deleteBidangPeminatan')->name('bidang-peminatan.delete');
        });
        
        Route::controller(UserController::class)->group(function () {
            Route::get('/user/ajax', 'ajax')->name('user.ajax');
            Route::post('/user/store', 'store');
            Route::get('/user/{id}', 'show');
            Route::post('/user/update/{id}', 'update');
            Route::delete('/user/{id}', 'destroy');
            Route::get('/user', 'index')->name('user.index');
        });

    });
    Route::middleware(['auth', 'role:superadmin|mahasiswa'])->group(function () {
        Route::get('/pengajuan-judul', [DataMaster::class, 'pengajuanJudul'])->name('pengajuan-judul.index');
        // Tambahkan route lain terkait pengajuan judul di sini
    });
});


require __DIR__.'/auth.php';

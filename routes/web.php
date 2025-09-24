<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataMaster;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DataMaster\MahasiswaController;
use App\Http\Controllers\TugasAkhirController; 
use App\Http\Controllers\SkProposalController;
use App\Http\Controllers\ValidasiSKController;

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

Route::get('/usulkan', function () {
    return view('commingsoon');
})->name('usulkan.index');

Route::get('/seminar', function () {
    return view('commingsoon');
})->name('seminar.index');

Route::get('/sidang', function () {
    return view('commingsoon');
})->name('sidang.index');


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
    Route::middleware(['auth', 'role:superadmin|admin prodi|mahasiswa|dosen'])->group(function () {
        Route::get('/dashboard-role', function () {
            return view('dashboard');
        })->name('dashboard.role');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('get-jenis-penelitian', [DataMaster::class, 'getJenisPenelitian']);
        Route::get('get-bidang-peminatan', [DataMaster::class, 'getBidangPeminatan']);
        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir', [TugasAkhirController::class, 'store'])->name('tugas-akhir.store');
        //Route::put('/tugas-akhir/{id}', [TugasAkhirController::class, 'update'])->name('tugas-akhir.update');
        Route::delete('/tugas-akhir/{id}', [TugasAkhirController::class, 'destroy'])->name('tugas-akhir.destroy');
        Route::get('/tugas-akhir/last', [TugasAkhirController::class, 'last'])->name('tugas-akhir.last');
        Route::get('/tugas-akhir/all', [TugasAkhirController::class, 'all'])->name('tugas-akhir.all');
        Route::get('/tugas-akhir/ajax', [TugasAkhirController::class, 'ajax'])->name('tugas-akhir.ajax');
        Route::get('/tugas-akhir/{id}/edit', [TugasAkhirController::class, 'edit'])->name('tugas-akhir.edit');
        Route::post('/tugas-akhir/{id}/update', [TugasAkhirController::class, 'update'])->name('tugas-akhir.update');
        Route::delete('/tugas-akhir/{id}', [TugasAkhirController::class, 'destroy'])->name('tugas-akhir.destroy');
        Route::get('/tugas-akhir/detail/{mahasiswaId}', [TugasAkhirController::class, 'detail']);
        Route::post('/tugas-akhir/{id}/revisi', [TugasAkhirController::class, 'revisi'])->name('tugas-akhir.revisi');

        //Route::get('/tugas-akhir/{id}/cetak-sk', [TugasAkhirController::class, 'cetakSk'])->name('tugas-akhir.cetakSk');
        Route::get('/tugas-akhir/{id}/revisi-detail', [TugasAkhirController::class, 'revisiDetail']);
    });
});


Route::post('/set-active-role', function(\Illuminate\Http\Request $request) {
    $role = $request->role;
    if (Auth::user()->hasRole($role)) {
        session(['active_role' => $role]);
    }
    return response()->json(['success' => true]);
})->name('set-active-role');

Route::post('/validasi-sk/create', [ValidasiSKController::class, 'create'])->name('validasi-sk.create');
Route::get('/validasi-sk/cetak/{id}', [ValidasiSKController::class, 'cetak'])->name('validasi-sk.cetak');

require __DIR__.'/auth.php';

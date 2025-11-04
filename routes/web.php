<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataMaster;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DataMaster\MahasiswaController;
use App\Http\Controllers\TugasAkhirController; 
use App\Http\Controllers\SkProposalController;
use App\Http\Controllers\ValidasiSKController;
use App\Http\Controllers\BankJudulController;
use App\Http\Controllers\RolePermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/validator', function() {
    return view('validator');
})->name('validator');

Route::post('/validator-sk', [App\Http\Controllers\ValidatorController::class, 'check'])
    ->name('validator.check');

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
    
    // ========== DATA MASTER (Role Manual) ==========
    Route::middleware(['role:superadmin|pimpinan'])->group(function () {
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

            // Jenis Penelitian
            Route::get('/jenis-penelitian', 'jenisPenelitian')->name('jenis-penelitian.index');
            Route::get('/jenis-penelitian/ajax', 'ajaxJenisPenelitian')->name('jenis-penelitian.ajax');
            Route::post('/jenis-penelitian/store', 'storeJenisPenelitian')->name('jenis-penelitian.store');
            Route::get('/jenis-penelitian/edit/{id}', 'editJenisPenelitian')->name('jenis-penelitian.edit');
            Route::post('/jenis-penelitian/update/{id}', 'updateJenisPenelitian')->name('jenis-penelitian.update');

            // Bidang Peminatan
            Route::get('/bidang-peminatan', 'bidangPeminatan')->name('bidang-peminatan.index');
            Route::get('/bidang-peminatan/ajax', 'ajaxBidangPeminatan')->name('bidang-peminatan.ajax');
            Route::post('/bidang-peminatan/store', 'storeBidangPeminatan')->name('bidang-peminatan.store');
            Route::get('/bidang-peminatan/edit/{id}', 'editBidangPeminatan')->name('bidang-peminatan.edit');
            Route::post('/bidang-peminatan/update/{id}', 'updateBidangPeminatan')->name('bidang-peminatan.update');
            Route::delete('/bidang-peminatan/delete/{id}', 'deleteBidangPeminatan')->name('bidang-peminatan.delete');
        });
        
        // User Management
        Route::controller(UserController::class)->group(function () {
            Route::get('/user', 'index')->name('user.index');
            Route::get('/user/ajax', 'ajax')->name('user.ajax');
            Route::post('/user/store', 'store');
            Route::get('/user/{id}', 'show');
            Route::post('/user/update/{id}', 'update');
            Route::delete('/user/{id}', 'destroy');
        });
    });

    // ========== ROLE & PERMISSION (Superadmin Only) ==========
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/role-permission', [RolePermissionController::class, 'index'])->name('role-permission.index');
        Route::get('/role-permission/ajax', [RolePermissionController::class, 'ajax']);
        Route::get('/role-permission/{id}', [RolePermissionController::class, 'show']);
        Route::post('/role-permission', [RolePermissionController::class, 'store']);
        Route::put('/role-permission/{id}', [RolePermissionController::class, 'update']);
        Route::delete('/role-permission/{id}', [RolePermissionController::class, 'destroy']);
        Route::post('/role-permission/{id}/give-permission', [RolePermissionController::class, 'givePermission'])->name('role-permission.give-permission');
    });

    // ========== BANK JUDUL & REKOMENDASI (Permission Dinamis) ==========
    // Bank Judul
    Route::get('/bank-judul', [BankJudulController::class, 'index'])
        ->middleware('permission:bank-judul-view')
        ->name('bank-judul.index');
    
    Route::get('/bank-judul/ajax', [BankJudulController::class, 'ajax'])
        ->middleware('permission:bank-judul-view')
        ->name('bank-judul.ajax');
    
    Route::get('/bank-judul/list', [BankJudulController::class, 'list'])
        ->middleware('permission:bank-judul-view')
        ->name('bank-judul.list');
    
    Route::post('/bank-judul', [BankJudulController::class, 'store'])
        ->middleware('permission:bank-judul-create')
        ->name('bank-judul.store');
    
    Route::put('/bank-judul/{id}', [BankJudulController::class, 'update'])
        ->middleware('permission:bank-judul-edit')
        ->name('bank-judul.update');
    
    Route::delete('/bank-judul/{id}', [BankJudulController::class, 'destroy'])
        ->middleware('permission:bank-judul-delete')
        ->name('bank-judul.destroy');

    // Rekomendasi Judul
    Route::get('/bank-judul/rekomendasi/ajax', [BankJudulController::class, 'ajaxRekomendasi'])
        ->middleware('permission:rekomendasi-judul-view')
        ->name('bank-judul.rekomendasi.ajax');
    
    Route::get('/bank-judul/rekomendasi/find', [BankJudulController::class, 'findRekomendasi'])
        ->middleware('permission:rekomendasi-judul-view');
    
    Route::post('/bank-judul/rekomendasi', [BankJudulController::class, 'storeRekomendasi'])
        ->middleware('permission:rekomendasi-judul-create');
    
    Route::put('/bank-judul/rekomendasi/{id}', [BankJudulController::class, 'updateRekomendasi'])
        ->middleware('permission:rekomendasi-judul-edit');
    
    Route::delete('/bank-judul/rekomendasi/{id}', [BankJudulController::class, 'destroyRekomendasi'])
        ->middleware('permission:rekomendasi-judul-delete');

    // ========== TUGAS AKHIR (Role Manual - Belum Diubah) ==========
    Route::middleware(['role:superadmin|admin prodi|mahasiswa|dosen'])->group(function () {
        Route::get('/dashboard-role', function () {
            return view('dashboard');
        })->name('dashboard.role');

        Route::get('get-jenis-penelitian', [DataMaster::class, 'getJenisPenelitian']);
        Route::get('get-bidang-peminatan', [DataMaster::class, 'getBidangPeminatan']);
        
        Route::get('/tugas-akhir', [TugasAkhirController::class, 'index'])->name('tugas-akhir.index');
        Route::post('/tugas-akhir', [TugasAkhirController::class, 'store'])->name('tugas-akhir.store');
        Route::delete('/tugas-akhir/{id}', [TugasAkhirController::class, 'destroy'])->name('tugas-akhir.destroy');
        Route::get('/tugas-akhir/last', [TugasAkhirController::class, 'last'])->name('tugas-akhir.last');
        Route::get('/tugas-akhir/all', [TugasAkhirController::class, 'all'])->name('tugas-akhir.all');
        Route::get('/tugas-akhir/ajax', [TugasAkhirController::class, 'ajax'])->name('tugas-akhir.ajax');
        Route::get('/tugas-akhir/{id}/edit', [TugasAkhirController::class, 'edit'])->name('tugas-akhir.edit');
        Route::post('/tugas-akhir/{id}/update', [TugasAkhirController::class, 'update'])->name('tugas-akhir.update');
        Route::get('/tugas-akhir/detail/{mahasiswaId}', [TugasAkhirController::class, 'detail']);
        Route::post('/tugas-akhir/{id}/revisi', [TugasAkhirController::class, 'revisi'])->name('tugas-akhir.revisi');
        Route::get('/tugas-akhir/{id}/revisi-detail', [TugasAkhirController::class, 'revisiDetail']);
        
        Route::get('/laporan', function () {
            return view('commingsoon');
        })->name('laporan.index');
    });
});

// Set Active Role
Route::post('/set-active-role', function(\Illuminate\Http\Request $request) {
    $role = $request->role;
    if (Auth::user()->hasRole($role)) {
        session(['active_role' => $role]);
    }
    return response()->json(['success' => true]);
})->name('set-active-role');

// Validasi SK
Route::post('/validasi-sk/create', [ValidasiSKController::class, 'create'])->name('validasi-sk.create');
Route::get('/validasi-sk/cetak/{id}', [ValidasiSKController::class, 'cetak'])->name('validasi-sk.cetak');

Route::get('/login', function() {
    return redirect()->route('welcome');
});

require __DIR__.'/auth.php';

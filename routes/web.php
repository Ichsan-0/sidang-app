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
        Route::get('/tahun', [DataMaster::class, 'tahun.index'])->name('tahun.index');
        Route::get('/prodi', [DataMaster::class, 'prodi.index'])->name('prodi.index');
        Route::get('/prodi/ajax', [DataMaster::class, 'ajaxProdi'])->name('prodi.ajax');
        Route::get('/fakultas', [DataMaster::class, 'fakultas.index'])->name('fakultas.index');
        Route::get('/fakultas/ajax', [DataMaster::class, 'ajaxFakultas'])->name('fakultas.ajax');
        Route::get('/tahun/ajax', [DataMaster::class, 'ajaxTahun'])->name('tahun.ajax');
    });

    Route::resource('mahasiswa', MahasiswaController::class);

    // Tambahkan route lain yang ada di sidebar jika perlu
});
    // Tambahkan route lain yang ada di sidebar jika perlu
});


require __DIR__.'/auth.php';

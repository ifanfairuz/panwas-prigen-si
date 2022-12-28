<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\KomponenKeuanganController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // file
    Route::name('file.')->prefix('file')->group(function () {
        Route::get('/view', [FileController::class, 'view'])->name('view');
        Route::get('/download', [FileController::class, 'download'])->name('download');
    });

    // administration
    Route::middleware(['isadministrator'])->name('administration.')->prefix('administration')->group(function () {
        Route::name('config.')->prefix('config')->group(function () {
            Route::get('/', [ConfigController::class, 'index'])->name('index');
            Route::name('dropbox.')->prefix('dropbox')->group(function () {
                Route::get('/get-access', [ConfigController::class, 'dropbox_getaccess'])->name('getaccess');
                Route::get('/granted', [ConfigController::class, 'dropbox_granted'])->name('granted');
            });
            Route::name('template.')->prefix('template')->group(function () {
                Route::post('/upload', [ConfigController::class, 'template_upload'])->name('upload');
            });
        });
        Route::name('users.')->prefix('users')->group(function () {
            Route::get('/', [UsersController::class, 'index'])->name('index');
            Route::get('/add', [UsersController::class, 'add'])->name('add');
            Route::post('/add', [UsersController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [UsersController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UsersController::class, 'delete'])->name('delete');
        });
    });

    // petugas
    Route::name('petugas.')->prefix('petugas')->group(function () {
        Route::get('/', [PetugasController::class, 'index'])->name('index');
        Route::get('/view/{id}', [PetugasController::class, 'view'])->name('view');
        Route::get('/add', [PetugasController::class, 'add'])->name('add');
        Route::post('/add', [PetugasController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [PetugasController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [PetugasController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PetugasController::class, 'delete'])->name('delete');
    });

    // surat masuk
    Route::name('surat.masuk.')->prefix('surat-masuk')->group(function () {
        Route::get('/', [SuratMasukController::class, 'index'])->name('index');
        Route::get('/view/{id}', [SuratMasukController::class, 'view'])->name('view');
        Route::get('/add', [SuratMasukController::class, 'add'])->name('add');
        Route::post('/add', [SuratMasukController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [SuratMasukController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [SuratMasukController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SuratMasukController::class, 'delete'])->name('delete');
    });

    // surat keluar
    Route::name('surat.keluar.')->prefix('surat-keluar')->group(function () {
        Route::get('/', [SuratKeluarController::class, 'index'])->name('index');
        Route::get('/view/{id}', [SuratKeluarController::class, 'view'])->name('view');
        Route::get('/add', [SuratKeluarController::class, 'add'])->name('add');
        Route::post('/add', [SuratKeluarController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [SuratKeluarController::class, 'edit'])->name('edit');
        Route::post('/edit/{id}', [SuratKeluarController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SuratKeluarController::class, 'delete'])->name('delete');
        Route::get('/get_urut', [SuratKeluarController::class, 'get_urut'])->name('get_urut');
        Route::post('/generate', [SuratKeluarController::class, 'generate'])->name('generate');
        Route::post('/generate_spd', [SuratKeluarController::class, 'generate_spd'])->name('generate_spd');
    });

    // keuangan
    Route::name('keuangan.')->prefix('keuangan')->group(function () {
        Route::name('komponen.')->prefix('komponen')->group(function () {
            Route::get('/', [KomponenKeuanganController::class, 'index'])->name('index');
            Route::get('/view/{id}', [KomponenKeuanganController::class, 'view'])->name('view');
            Route::get('/add', [KomponenKeuanganController::class, 'add'])->name('add');
            Route::post('/add', [KomponenKeuanganController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [KomponenKeuanganController::class, 'edit'])->name('edit');
            Route::post('/edit/{id}', [KomponenKeuanganController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [KomponenKeuanganController::class, 'delete'])->name('delete');
        });

        Route::get('/pembukuan', function () {
            return Inertia::render('Dashboard');
        })->name('pembukuan.index');
    });
});

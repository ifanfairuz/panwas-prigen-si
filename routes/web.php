<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
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

    // surat masuk
    Route::name('surat.masuk.')->prefix('surat-masuk')->group(function () {
        Route::get('/', [SuratMasukController::class, 'index'])->name('index');
        Route::get('/view/{id}', [SuratMasukController::class, 'view'])->name('view');
        Route::get('/add', [SuratMasukController::class, 'add'])->name('add');
        Route::post('/add', [SuratMasukController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [SuratMasukController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [SuratMasukController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SuratMasukController::class, 'delete'])->name('delete');
    });

    // surat keluar
    Route::name('surat.keluar.')->prefix('surat-keluar')->group(function () {
        Route::get('/', [SuratKeluarController::class, 'index'])->name('index');
        Route::get('/view/{id}', [SuratKeluarController::class, 'view'])->name('view');
        Route::get('/add', [SuratKeluarController::class, 'add'])->name('add');
        Route::post('/add', [SuratKeluarController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [SuratKeluarController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [SuratKeluarController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [SuratKeluarController::class, 'delete'])->name('delete');
    });

    // keuangan
    Route::get('/keuangan', function () {
        return Inertia::render('Dashboard');
    })->name('keuangan.index');
});

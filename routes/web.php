<?php

use App\Http\Controllers\DashboardController;
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

    // administration
    Route::middleware(['isadministrator'])->name('administration.')->prefix('administration')->group(function () {
        Route::name('users.')->prefix('users')->group(function (){
            Route::get('/', [UsersController::class, 'index'])->name('index');
            Route::get('/add', [UsersController::class, 'add'])->name('add');
            Route::post('/add', [UsersController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
            Route::put('/edit/{id}', [UsersController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UsersController::class, 'delete'])->name('delete');
        });
    });

    // surat masuk
    Route::get('/surat-masuk', function () {
        return Inertia::render('Dashboard');
    })->name('surat.masuk.index');

    // surat keluar
    Route::get('/surat-keluar', function () {
        return Inertia::render('Dashboard');
    })->name('surat.keluar.index');

    // keuangan
    Route::get('/keuangan', function () {
        return Inertia::render('Dashboard');
    })->name('keuangan.index');
});

<?php
// routes/web.php  (CORREGIDO: sin lógica suelta al final)

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\GarantiaController;
use App\Http\Controllers\Admin\SeguimientoGarantiaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('clientes', ClienteController::class)->names('admin.clientes');
    Route::resource('garantias', GarantiaController::class)->names('admin.garantias');

    Route::post('garantias/{garantia}/seguimientos', [SeguimientoGarantiaController::class, 'store'])
        ->name('admin.garantias.seguimientos.store');

    Route::delete('seguimientos/{seguimientoGarantia}', [SeguimientoGarantiaController::class, 'destroy'])
        ->name('admin.seguimientos.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MotoristaController;
// use App\Http\Controllers\VeiculoController;
// use App\Http\Controllers\PontoDeParadaController;
// use App\Http\Controllers\RotaController;
// use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Route;

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

Route::resource('motoristas', MotoristaController::class);
// Route::resource('veiculos', VeiculoController::class);
// Route::resource('pontos-de-parada', PontoDeParadaController::class);
// Route::resource('rotas', RotaController::class);
// Route::resource('viagens', ViagemController::class);

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\PontoDeParadaController;
use App\Http\Controllers\RotaController;
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
Route::resource('veiculos', VeiculoController::class);
Route::resource('ponto_de_paradas', PontoDeParadaController::class);
Route::resource('rotas', RotaController::class);
Route::post('rotas/{rota}/pontos', [RotaController::class, 'vincularPonto'])->name('rotas.pontos.store');
Route::put('rotas/{rota}/pontos', [RotaController::class, 'atualizarPontos'])->name('rotas.pontos.update');
Route::delete('rotas/{rota}/pontos/{pontoDeParada}', [RotaController::class, 'desvincularPonto'])->name('rotas.pontos.destroy');
// Route::resource('viagens', ViagemController::class);

require __DIR__.'/auth.php';

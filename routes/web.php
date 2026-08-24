<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\PassageiroController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\PontoDeParadaController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\ViagemController;
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
Route::resource('viagems', ViagemController::class);
Route::get('/passageiros', [PassageiroController::class, 'index']);
Route::post('/passageiros/viagem/{viagem}/pontos', [PassageiroController::class, 'selecionarPontos'])->name('passageiros.selecionar-pontos');
Route::delete('/passageiros/viagem/{viagem}/cancelar', [PassageiroController::class, 'cancelarReserva']);
Route::get('/passageiros/scanner', fn() => view('passageiros.scanner'));
Route::get('/viagens/checkin/{codigo_qr}', [PassageiroController::class, 'registrarPresencaViaQr'])->name('viagens.checkin');

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\PassageiroController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\PontoDeParadaController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\ViagemController;
use App\Http\Controllers\LeitorQrController;
use App\Http\Controllers\PortalMotoristaController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckMotorista;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [PassageiroController::class, 'index'])->middleware(['verified'])->name('dashboard');
    Route::get('/dashboard/historico', [PassageiroController::class, 'historico'])->name('passageiros.historico');
    Route::get('/dashboard/viagens/{viagem}', [PassageiroController::class, 'selecionarPontos'])->name('passageiros.selecionar-pontos');
    Route::post('/dashboard/viagens/{viagem}', [PassageiroController::class, 'salvarPontos'])->name('passageiros.salvar-pontos');
    Route::delete('/dashboard/viagens/{viagem}/cancelar', [PassageiroController::class, 'cancelar'])->name('passageiros.cancelar');
    Route::get('/dashboard/scanner', [LeitorQrController::class, 'exibirScanner'])->name('passageiros.scanner');
    Route::post('/dashboard/scanner/validar', [LeitorQrController::class, 'validarQrCode'])->name('passageiros.scanner.validar');
    Route::get('/rotas/autocomplete', [RotaController::class, 'autocomplete'])->name('rotas.autocomplete');
    Route::get('/motoristas/autocomplete', [MotoristaController::class, 'autocomplete'])->name('motoristas.autocomplete');
    Route::get('/veiculos/autocomplete', [VeiculoController::class, 'autocomplete'])->name('veiculos.autocomplete');
    Route::get('/rotas/{rota}/pontos/autocomplete', [PontoDeParadaController::class, 'autocomplete'])->name('rotas.pontos.autocomplete');
    
    Route::middleware([CheckAdmin::class])
        ->group(function () {
            Route::resource('users', UserController::class);
            Route::get('/usuarios/autocomplete', [UserController::class, 'autocomplete'])->name('usuarios.autocomplete');
            Route::resource('motoristas', MotoristaController::class);
            Route::resource('veiculos', VeiculoController::class);
            Route::resource('ponto-de-paradas', PontoDeParadaController::class)->names('ponto_de_paradas');
            Route::resource('rotas', RotaController::class);
            Route::post('rotas/{rota}/pontos', [RotaController::class, 'vincularPonto'])->name('rotas.pontos.store');
            Route::put('rotas/{rota}/pontos', [RotaController::class, 'atualizarPontos'])->name('rotas.pontos.update');
            Route::delete('rotas/{rota}/pontos/{pontoDeParada}', [RotaController::class, 'desvincularPonto'])->name('rotas.pontos.destroy');
            Route::resource('viagems', ViagemController::class);
            Route::get('viagems/{viagem}/duplicate', [ViagemController::class, 'duplicate'])->name('viagems.duplicate');
        });

    Route::middleware([CheckMotorista::class])
        ->prefix('portal-motorista')
        ->name('portal_motorista.')
        ->group(function () {
            Route::get('/', [PortalMotoristaController::class, 'index'])->name('index');
            Route::get('/historico', [PortalMotoristaController::class, 'historico'])->name('historico');
            Route::get('/viagens/{viagem}', [PortalMotoristaController::class, 'show'])->name('show');
            Route::post('/viagens/{viagem}/iniciar', [PortalMotoristaController::class, 'iniciar'])->name('iniciar');
            Route::post('/viagens/{viagem}/finalizar', [PortalMotoristaController::class, 'finalizar'])->name('finalizar');
            Route::get('/viagens/{viagem}/imprimir', [PortalMotoristaController::class, 'imprimir'])->name('imprimir');
        });
});

require __DIR__ . '/auth.php';

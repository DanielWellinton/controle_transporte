<?php

namespace Database\Seeders;

use App\Models\Motorista;
use App\Models\PontoDeParada;
use App\Models\Rota;
use App\Models\User;
use App\Models\Veiculo;
use App\Models\Viagem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ViagemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rota = Rota::first();
        $motorista = Motorista::first();
        $veiculo = Veiculo::first();

        if (!$rota || !$motorista || !$veiculo) {
            return;
        }

        // Criar uma viagem agendada gerando o codigo_qr explicitamente
        $viagem = Viagem::create([
            'codigo_qr' => (string) Str::uuid(),
            'data_hora_saida' => now()->addHours(2),
            'data_hora_chegada' => now()->addHours(3),
            'rota_id' => $rota->id,
            'motorista_id' => $motorista->id,
            'veiculo_id' => $veiculo->id,
            'ativo' => true,
        ]);

        // Vincular passageiros na viagem
        $passageiros = User::where('email', 'like', '%@gmail.com')->get();
        $pontoSaida = PontoDeParada::first();
        $pontoChegada = PontoDeParada::skip(2)->first();

        foreach ($passageiros as $passageiro) {
            $viagem->passageiros()->attach($passageiro->id, [
                'ponto_de_parada_saida_id' => $pontoSaida?->id,
                'ponto_de_parada_chegada_id' => $pontoChegada?->id,
                'data_hora_saida' => now()->addHours(2),
                'data_hora_chegada' => now()->addHours(3),
            ]);
        }
    }
}

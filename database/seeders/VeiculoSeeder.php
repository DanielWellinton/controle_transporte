<?php

namespace Database\Seeders;

use App\Models\Veiculo;
use Illuminate\Database\Seeder;

class VeiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $veiculos = [
            ['descricao' => 'Ônibus Escolar Volare V8', 'numero_passageiros' => 32, 'placa' => 'ABC-1234', 'ativo' => true],
            ['descricao' => 'Micro-ônibus Mercedes Benz', 'numero_passageiros' => 24, 'placa' => 'XYZ-5678', 'ativo' => true],
            ['descricao' => 'Van Renault Master', 'numero_passageiros' => 15, 'placa' => 'KGW-9012', 'ativo' => true],
        ];

        foreach ($veiculos as $veiculo) {
            Veiculo::firstOrCreate(['placa' => $veiculo['placa']], $veiculo);
        }
    }
}

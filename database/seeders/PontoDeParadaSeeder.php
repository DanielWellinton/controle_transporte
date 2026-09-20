<?php

namespace Database\Seeders;

use App\Models\PontoDeParada;
use Illuminate\Database\Seeder;

class PontoDeParadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pontos = [
            ['descricao' => 'Praça Central', 'latitude' => -19.9167, 'longitude' => -43.9345, 'ativo' => true],
            ['descricao' => 'Escola Municipal Bairro Novo', 'latitude' => -19.9210, 'longitude' => -43.9400, 'ativo' => true],
            ['descricao' => 'Posto de Saúde Central', 'latitude' => -19.9280, 'longitude' => -43.9450, 'ativo' => true],
            ['descricao' => 'Terminal Rodoviário', 'latitude' => -19.9320, 'longitude' => -43.9500, 'ativo' => true],
            ['descricao' => 'Campus Universitário', 'latitude' => -19.9400, 'longitude' => -43.9600, 'ativo' => true],
        ];

        foreach ($pontos as $ponto) {
            PontoDeParada::firstOrCreate(['descricao' => $ponto['descricao']], $ponto);
        }
    }
}

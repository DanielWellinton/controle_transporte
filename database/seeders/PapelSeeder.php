<?php

namespace Database\Seeders;

use App\Models\Papel;
use Illuminate\Database\Seeder;

class PapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $papeis = [
            ['descricao' => 'Admin', 'ativo' => true],
            ['descricao' => 'Passageiro', 'ativo' => true],
            ['descricao' => 'Motorista', 'ativo' => true],
        ];

        foreach ($papeis as $papel) {
            Papel::firstOrCreate(['descricao' => $papel['descricao']], $papel);
        }
    }
}

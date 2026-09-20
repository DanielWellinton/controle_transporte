<?php

namespace Database\Seeders;

use App\Models\Motorista;
use App\Models\User;
use Illuminate\Database\Seeder;

class MotoristaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $joao = User::where('email', 'joao.motorista@transporte.com')->first();
        $carlos = User::where('email', 'carlos.motorista@transporte.com')->first();

        if ($joao) {
            Motorista::firstOrCreate(
                ['usuario_id' => $joao->id],
                [
                    'cnh' => '12345678900',
                    'data_validade_cnh' => now()->addYears(3),
                    'ativo' => true,
                ]
            );
        }

        if ($carlos) {
            Motorista::firstOrCreate(
                ['usuario_id' => $carlos->id],
                [
                    'cnh' => '09876543211',
                    'data_validade_cnh' => now()->addYears(2),
                    'ativo' => true,
                ]
            );
        }
    }
}

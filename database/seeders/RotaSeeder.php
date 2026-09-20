<?php

namespace Database\Seeders;

use App\Models\PontoDeParada;
use App\Models\Rota;
use Illuminate\Database\Seeder;

class RotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rotaLinha1 = Rota::firstOrCreate(['descricao' => 'Rota 01 - Centro / Campus'], ['ativo' => true]);
        $rotaLinha2 = Rota::firstOrCreate(['descricao' => 'Rota 02 - Escolar Bairro Novo'], ['ativo' => true]);

        $p1 = PontoDeParada::where('descricao', 'Praça Central')->first();
        $p2 = PontoDeParada::where('descricao', 'Escola Municipal Bairro Novo')->first();
        $p3 = PontoDeParada::where('descricao', 'Posto de Saúde Central')->first();
        $p4 = PontoDeParada::where('descricao', 'Terminal Rodoviário')->first();
        $p5 = PontoDeParada::where('descricao', 'Campus Universitário')->first();

        // Vincula pontos com ordem
        if ($rotaLinha1 && $p1 && $p3 && $p5) {
            $rotaLinha1->pontosDeParada()->syncWithoutDetaching([
                $p1->id => ['ordem' => 1, 'ativo' => true],
                $p3->id => ['ordem' => 2, 'ativo' => true],
                $p5->id => ['ordem' => 3, 'ativo' => true],
            ]);
        }

        if ($rotaLinha2 && $p1 && $p2 && $p4) {
            $rotaLinha2->pontosDeParada()->syncWithoutDetaching([
                $p1->id => ['ordem' => 1, 'ativo' => true],
                $p2->id => ['ordem' => 2, 'ativo' => true],
                $p4->id => ['ordem' => 3, 'ativo' => true],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Papel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $papelAdmin = Papel::where('descricao', 'Admin')->first();
        $papelPassageiro = Papel::where('descricao', 'Passageiro')->first();
        $papelMotorista = Papel::where('descricao', 'Motorista')->first();

        // 1. Criar Administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@transporte.com'],
            [
                'name' => 'Administrador do Sistema',
                'password' => Hash::make('password'),
                'data_nascimento' => '1985-05-10',
                'telefone' => '(31) 99999-0000',
            ]
        );
        $admin->papeis()->syncWithoutDetaching([
            $papelAdmin->id => ['data_hora_inicio' => now(), 'ativo' => true],
            $papelPassageiro->id => ['data_hora_inicio' => now(), 'ativo' => true],
        ]);

        // 2. Criar Motoristas
        $motoristasData = [
            ['name' => 'João Silva', 'email' => 'joao.motorista@transporte.com'],
            ['name' => 'Carlos Andrade', 'email' => 'carlos.motorista@transporte.com'],
        ];

        foreach ($motoristasData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'data_nascimento' => '1980-03-15',
                    'telefone' => '(31) 98888-1111',
                ]
            );
            $user->papeis()->syncWithoutDetaching([
                $papelMotorista->id => ['data_hora_inicio' => now(), 'ativo' => true],
                $papelPassageiro->id => ['data_hora_inicio' => now(), 'ativo' => true],
            ]);
        }

        // 3. Criar Passageiros Regulares
        $passageirosData = [
            ['name' => 'Maria Oliveira', 'email' => 'maria@gmail.com'],
            ['name' => 'Lucas Santos', 'email' => 'lucas@gmail.com'],
            ['name' => 'Ana Costa', 'email' => 'ana@gmail.com'],
            ['name' => 'Fernanda Souza', 'email' => 'fernanda@gmail.com'],
        ];

        foreach ($passageirosData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'data_nascimento' => '2001-08-20',
                    'telefone' => '(31) 97777-2222',
                ]
            );
            $user->papeis()->syncWithoutDetaching([
                $papelPassageiro->id => ['data_hora_inicio' => now(), 'ativo' => true],
            ]);
        }
    }
}

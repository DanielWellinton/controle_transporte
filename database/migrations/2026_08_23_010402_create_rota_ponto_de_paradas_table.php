<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rota_ponto_de_paradas', function (Blueprint $table) {
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            $table->foreignId('ponto_de_parada_id')->constrained('ponto-de-paradas')->onDelete('cascade');
            
            $table->integer('ordem')->default(1);
            $table->boolean('ativo')->default(true);
            
            $table->timestamps();

            // Chave Primária Composta (PK composta)
            $table->primary(['rota_id', 'ponto_de_parada_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rota_ponto_de_paradas');
    }
};

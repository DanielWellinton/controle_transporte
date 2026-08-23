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
        Schema::create('passageiros', function (Blueprint $table) {
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('viagem_id')->constrained('viagems')->onDelete('cascade');

            $table->foreignId('ponto_de_parada_saida_id')->constrained('ponto_de_paradas')->onDelete('cascade');
            $table->foreignId('ponto_de_parada_chegada_id')->nullable()->constrained('ponto_de_paradas')->onDelete('cascade');

            $table->dateTime('data_hora_saida');
            $table->dateTime('data_hora_chegada')->nullable();

            $table->primary(['usuario_id', 'viagem_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passageiros');
    }
};

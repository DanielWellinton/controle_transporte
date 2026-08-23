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
        Schema::create('viagems', function (Blueprint $table) {
            $table->id();
            $table->dateTime('data_hora_saida');
            $table->dateTime('data_hora_chegada')->nullable();
            
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            $table->foreignId('motorista_id')->constrained('motoristas')->onDelete('cascade');
            $table->foreignId('veiculo_id')->constrained('veiculos')->onDelete('cascade');
            
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viagems');
    }
};

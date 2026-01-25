<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('previsoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('no action');
            $table->foreignId('material_id')->constrained('materiais')->onDelete('no action');
            $table->foreignId('patio_id')->constrained('patios')->onDelete('no action');
            $table->integer('quantidade_prevista')->default(0);
            $table->foreignId('unidade_medida_id')->constrained('unidades_medida')->onDelete('no action');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('previsoes');
    }
};


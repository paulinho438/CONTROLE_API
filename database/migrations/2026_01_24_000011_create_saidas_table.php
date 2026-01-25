<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('no action');
            $table->foreignId('material_id')->constrained('materiais')->onDelete('no action');
            $table->foreignId('patio_id')->constrained('patios')->onDelete('no action');
            $table->foreignId('destino_patio_id')->nullable()->constrained('patios')->onDelete('set null');
            $table->string('tipo_movimentacao')->nullable();
            $table->string('nota_fiscal')->nullable();
            $table->decimal('valor', 12, 2)->nullable();
            $table->date('data_saida')->nullable();
            $table->integer('quantidade');
            $table->foreignId('unidade_medida_id')->constrained('unidades_medida')->onDelete('no action');
            $table->string('numero_romaneio')->nullable();
            $table->foreignId('responsavel_colaborador_id')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->string('destino')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saidas');
    }
};


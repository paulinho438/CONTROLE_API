<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('no action');
            $table->foreignId('material_id')->constrained('materiais')->onDelete('no action');
            $table->foreignId('patio_id')->constrained('patios')->onDelete('no action');
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->onDelete('set null');
            $table->foreignId('nota_fiscal_id')->nullable()->constrained('notas_fiscais')->onDelete('set null');
            $table->decimal('valor', 12, 2)->nullable();
            $table->date('data_emissao')->nullable();
            $table->integer('quantidade');
            $table->foreignId('unidade_medida_id')->constrained('unidades_medida')->onDelete('no action');
            $table->date('data_recebimento')->nullable();
            $table->string('numero_romaneio')->nullable();
            $table->decimal('peso_nota', 12, 3)->nullable();
            $table->foreignId('responsavel_colaborador_id')->nullable()->constrained('colaboradores')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entradas');
    }
};


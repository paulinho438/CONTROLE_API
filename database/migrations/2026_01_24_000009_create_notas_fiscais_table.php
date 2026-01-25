<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->onDelete('set null');
            $table->string('razao_social')->nullable();
            $table->string('cnpj_cpf')->nullable();
            $table->string('numero_nota')->nullable();
            $table->date('data_emissao')->nullable();
            $table->date('data_recebimento')->nullable();
            $table->decimal('peso_nota', 12, 3)->nullable();
            $table->decimal('valor', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas_fiscais');
    }
};


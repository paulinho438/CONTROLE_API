<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('no action');
            $table->string('nome');
            $table->string('aplicacao')->nullable();
            $table->string('cor_predominante')->nullable();
            $table->decimal('comprimento_m', 10, 3)->nullable();
            $table->decimal('largura_m', 10, 3)->nullable();
            $table->decimal('altura_m', 10, 3)->nullable();
            $table->decimal('massa_kg', 10, 3)->nullable();
            $table->decimal('densidade_kmm', 10, 3)->nullable();
            $table->integer('estoque_previsto')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materiais');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('status')->default('pending');
            $table->integer('total_rows')->default(0);
            $table->integer('inserted_rows')->default(0);
            $table->integer('updated_rows')->default(0);
            $table->integer('ignored_rows')->default(0);
            $table->integer('error_rows')->default(0);
            $table->json('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_batches');
    }
};

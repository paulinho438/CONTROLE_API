<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->date('data_cadastro')->nullable()->after('nome');
        });
    }

    public function down()
    {
        Schema::table('grupos', function (Blueprint $table) {
            $table->dropColumn('data_cadastro');
        });
    }
};


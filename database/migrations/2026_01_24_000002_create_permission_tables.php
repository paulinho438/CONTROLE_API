<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permitems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::create('permgroups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('permgroup_permitem', function (Blueprint $table) {
            $table->foreignId('permgroup_id')->constrained('permgroups')->onDelete('cascade');
            $table->foreignId('permitem_id')->constrained('permitems')->onDelete('cascade');
            $table->primary(['permgroup_id', 'permitem_id']);
        });

        Schema::create('permgroup_user', function (Blueprint $table) {
            $table->foreignId('permgroup_id')->constrained('permgroups')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->primary(['permgroup_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('permgroup_user');
        Schema::dropIfExists('permgroup_permitem');
        Schema::dropIfExists('permgroups');
        Schema::dropIfExists('permitems');
    }
};


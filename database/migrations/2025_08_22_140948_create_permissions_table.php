<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('display_name', 150);
            $table->text('description')->nullable();
            $table->string('module', 50);
            $table->string('action', 50);
            $table->timestamps();

            $table->index('name');
            $table->index('module');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};

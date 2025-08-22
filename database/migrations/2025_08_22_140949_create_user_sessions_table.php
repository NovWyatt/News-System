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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token_hash');
            $table->string('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at');
            $table->timestamp('last_used_at')->useCurrent();
            $table->timestamps();

            $table->index('user_id');
            $table->index('token_hash');
            $table->index('expires_at');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_sessions');
    }
};

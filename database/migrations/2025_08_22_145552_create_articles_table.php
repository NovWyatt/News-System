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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['author_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('uploader')->constrained()->onDelete('cascade');
            $table->string('caption')->nullable();
            $table->string('description')->nullable();
            $table->integer('privacy')->default(0);
            $table->boolean('archived')->default(false);
            $table->boolean('allow_comment')->default(true);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('post_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('comment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('follow_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('title')->nullable();
            $table->text('message')->nullable();
            $table->string('group_key')->nullable();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_viewed')->default(false);
            $table->string('link')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
            $table->index('group_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};

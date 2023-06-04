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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');

            $table->foreignId('post_id')
            ->constrained('posts')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->bigInteger('reports_number')->default(0);

            $table->foreignId('commenter_id')
            ->constrained('users')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->bigInteger('likes_counts')->nullable();
            $table->bigInteger('dislikes_counts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

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
            $table->string('title')->nullable();
            $table->text('content');
            $table->enum('type',[
                'Road map',
                'Job Opportunities',
                'Story',
                'Regular',
                'Question',
                'Advise',
                'CV',
                'Accepted Challenge',
                'Challenge'
            ]);

            $table->bigInteger('likes_counts')
                ->default(0)
                ->unsigned();

            $table->bigInteger('dislikes_counts')
                ->default(0)
                ->unsigned();

            $table->integer('reports_number')
                ->default(0)
                ->unsigned();

            $table->morphs('location');

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

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

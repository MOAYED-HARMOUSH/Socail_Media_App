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
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->enum('specialty',['AI', 'Software', 'Cyber_Security', 'Network']);
            $table->set('section',['AI', 'Software', 'Cyber_Security', 'Network']);
            $table->set('framework',['AI', 'Software', 'Cyber_Security', 'Network'])->nullable();
            $table->json('language');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};

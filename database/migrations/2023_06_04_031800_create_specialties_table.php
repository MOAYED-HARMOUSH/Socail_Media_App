<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->enum('specialty', [
                'AI',
                'Software',
                'Cyber_Security',
                'Network'
            ]);
            $table->set('section', [
                'Frontend',
                'Backend',
                'MobileDevelopment',
                'FullStack',
                'DBAnalysis',
                'DevOps',
                'GamesDevelopment',
                'SoftwareAnalysis',
                'QualityAssurance',
                'CloudArchitecture',
                'MachineLearning',
                'DeepLearning',
                'Robotics',
                'NaturalLanguageProcessing',
                'ExpertSystems',
                'FuzzyLogic',
                'ComputerVision',
                'DataScientist',
                'ArtificialGeneralIntelligence',
                'NaturalNetwork',
                'NetworkSecurity',
                'CloudSecurity',
                'EndPointSecurity',
                'MobileSecurity',
                'IoTSecurity',
                'ApplicationSecurity',
                'ZeroTrust',
                'NetworkAdministrators',
                'NetworkEngineer',
                'NetworkAnalyst',
                'SystemsAdministrators',
                'NetworkTechnician',
            ]);
            $table->set('framework', [
                'AI',
                'Software',
                'Cyber_Security',
                'Network'
            ])->nullable();
            $table->set('language', [
                'AI',
                'Software',
                'Cyber_Security',
                'Network'
            ]);

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

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

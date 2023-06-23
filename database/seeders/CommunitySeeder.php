<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'AI',
            'Software',
            'Cyber_Security',
            'Network',
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
            'Flutter',
            'AngularJs',
            'ReactJs',
            'Laravel',
            'ReactNative',
            'Net',
            'jQuery',
            'Asp',
            'Django',
            'Xamarin',
            'BootStrap',
            'Dart',
            'Php',
            'Java',
            'Cpp',
            'Python',
            'JavaScript',
            'TypeScript',
            'SQL',
            'Kotlin',
            'Swift',
            'Rust',
            'MatLab',
            'Scala'
        ];
        for ($i = 0; $i < 60; $i++) {
            DB::table('communities')->insert([
                'name' => $name[$i],
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
        }
    }
}

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
        $name = array_merge(
            config('specialty.specialty'),
            config('specialty.section'),
            config('specialty.framework'),
            config('specialty.language'),
        );

        for ($i = 0; $i < 60; $i++) {
            DB::table('communities')->insert([
                'name' => $name[$i] . ' Space',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $senders = User::factory()
            ->count(5)
            ->hasSpecialty()
            ->has(
                User::factory()
                    ->count(3)
                    ->hasSpecialty(),
                'senders'
            )
            ->create();
    }
}

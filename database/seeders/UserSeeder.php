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
            ->hasSenders()
            ->create();

        foreach ($senders as $sender) {
            $sender->copyMedia('C:\Users\yesma\OneDrive\Desktop\-5983087055229533098_121.jpg')
                ->toMediaCollection('avatar');
        }
    }
}

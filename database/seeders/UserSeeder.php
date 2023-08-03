<?php

namespace Database\Seeders;

use App\Models\Page;
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
                $receivers = User::factory()
                    ->count(3)
                    ->hasSpecialty(),
                'senders'
            )
            ->create();

        $page = Page::factory()
            ->count(3)
            ->for($senders)
            ->create();

        $receivers->has($page, 'memberPages')->create();
    }
}

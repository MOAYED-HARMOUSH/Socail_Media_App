<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Http\Controllers\Api\FriendController;
use App\Models\User;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $friend = new FriendController;
        foreach ($users as $user) {
            foreach ($users as $sendtothis) {
                $received = $user->receivers()->where('friends.sender', $sendtothis->id)->first();
                if ($user != $sendtothis && empty($received)) {
                    $user->senders()->attach($sendtothis->id);
                }
            }

        }
        foreach ($users as $user) {
            foreach ($users as $recievetothis) {
                $user->receivers()
                    ->where('friends.sender', $recievetothis->id)
                    ->update(['is_approved' => true]);

            }
        }

    }
}

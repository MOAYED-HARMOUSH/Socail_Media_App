<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Models\User;
use Database\Seeders\MainSeeder;
use Illuminate\Database\Seeder;

class UserController extends Controller
{
    public function getAvatar(Request $request) //for Test Only
    {
        return $request->user()->getFirstMedia('avatars');
    }

    public function completeInfo(Request $request)
    {
        if ($request->has('study_semester'))
            $request->user()->student()->create($request->all());

        if ($request->has('companies'))
            $request->user()->expert()->create([
                'companies' => json_encode(explode(',', $request->companies)),
                'years_as_expert' => $request->years_as_expert,
                'work_at_company' => $request->work_at_company
            ]);

        return $request->user()->update($request->all());
    }
    public function Editspecialty(Request $request) //test
    {

          return  $request->user()->specialty()->update($request->all());

    }


    public function createRandomUsers($count)
    {
        $factory = User::factory();

        for ($i = 0; $i < $count; $i++) {
            $user = $factory->create();
            $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;
            $arr[$i]=$token;
            $users[$i]=$user;
        }
        app()->make(\Database\Seeders\MainSeeder::class)->run();

        return $arr;
    }

}




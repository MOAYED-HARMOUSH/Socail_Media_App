<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Models\Specialty;

class AuthController extends Controller
{
    public function signUp(AuthRequest $request)
    {
        $request->validated();
        // $user = User::create($request->all());
        // $user->createToken('Sign up')->plainTextToken;
        // var_dump($request->language);
        return var_dump(Specialty::create([
            'specialty' => $request->specialty,
            'section' => $request->section,
            'framework' => $request->framework,
            'language' => "[\"".$request->language[0]."\",\"".$request->language[0]."\"]"
        ])->framework);
        // return Specialty::query("INSERT INTO `specialties`(`id`, `specialty`, `section`, `framework`, `language`, `created_at`, `updated_at`) VALUES (4,,'AI,Network','{"ff":"dd"}','2023-06-05 18:49:45','2023-06-05 18:49:45')");
        // $user->save();
    }

    public function logIn(Request $request)
    {
        # code...
    }

    public function hasLoggedIn(Request $request)
    {
        if ($request->user())
            return to_route('Home Page');
    }

    public function logOut(Request $request)
    {
        # code...
    }

    public function deleteAccount(Request $request)
    {
        # code...
    }
}

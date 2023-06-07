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
        $user = User::create($request->all());
        $user->createToken('Sign up')->plainTextToken;
        return var_dump(Specialty::create([
            'specialty' => $request->specialty,
            'section' => $request->section,
            'framework' => $request->framework,
            'language' => $request->language
        ])->framework);
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

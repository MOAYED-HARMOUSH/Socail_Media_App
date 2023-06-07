<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signUp(AuthRequest $request)
    {
        $request->validated();
        $user = User::create($request->all());
        $token = $user->createToken('Sign up',[''],now()->addYear())->plainTextToken;
        $user->specialty()->create($request->all());
        return response()->json([
            'token' => $token
        ]);
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

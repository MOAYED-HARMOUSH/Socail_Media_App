<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signUp(AuthRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;
        $user->specialty()->create($request->all());
        return response()->json([
            'token' => $token
        ]);
    }

    public function logIn(Request $request)
    {
        // $data = $request->validate([
        //     'email' => 'bail|required|email',
        //     'password' => 'bail|required|string|min:8'
        // ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'Message' => "this $request->email email doesn't Exist or Invalid , Please Make sure you Enter the right one or Sign up"
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'Message' => 'Password Incorrect Please Make sure you Enter the right one or Press Forget Password Button'
            ]);
        }

        $token = $user->createToken('Log in', [''], now()->addYear())->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
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

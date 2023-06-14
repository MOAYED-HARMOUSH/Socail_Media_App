<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function signUp(AuthRequest $request)
    {
        $user = User::create($request->all());
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
                'Message' => "this $request->email email doesn't Exist or Invalid , Please Make sure you Enter the right one or Sign up",
                'data' => []
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'Message' => 'Password Incorrect Please Make sure you Enter the right one or Press Forget Password Button',
                'data' => []
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
        $request->user()->tokens()->delete();
        return response()->json([
            'Message' => 'Logged Out Successfully'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $this->logOut($request);
        $request->user()->delete();
        return response()->json([
            'Message' => 'Signed Out Successfully'
        ]);
    }

    public function completeInfo(Request $request)
    {
        if ($request->has('study_semester'))
            $request->user()->student()->create($request->all());

        if ($request->has('companies'))
            $request->user()->expert()->create($request->all());

        return $request->user()->update($request->all());
    }
}

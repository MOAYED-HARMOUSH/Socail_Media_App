<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function signUp(AuthRequest $request)
    {
        $user = User::create($request->all());

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('avatars');
        }

        $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;

        //event(new Registered($user));

        $user->specialty()->create($request->all());

        CommunityController::addUserToCommunity($request, $user);

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logIn(Request $request)
    {
        // $request->validate([
        //     'email' => 'bail|required|email',
        //     'password' => 'bail|required|string|min:8'
        // ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'Message' => "this $request->email email doesn't Exist or Invalid , Please Make sure you Enter the right one or Sign up",
                'user' => []
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'Message' => 'Password Incorrect Please Make sure you Enter the right one or Press Forget Password Button',
                'user' => []
            ]);
        }

        $token = $user->createToken('Log in', [''], now()->addYear())->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
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
        $user = $request->user();

        CommunityController::subSubscriberCounts($user);

        PageController::subMemberCounts($user);

        $user->delete();
        return response()->json([
            'Message' => 'Signed Out Successfully'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Mail\ResetPasswordCode;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    /**
     * Summary of forgotPassword
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|bail|exists:users',
        ]);

        /* Don't Uncomment this
        $user = User::where('email', $request->email)->first();
        Password::deleteToken($user);
        Password::createToken($user);
        Password::sendResetLink($request->only('email'));*/

        $data['token'] = mt_rand(100000, 999999);

        $data['created_at'] = now();

        $reset_builder = DB::table('password_reset_tokens')->where('email', $data['email']);
        $record = $reset_builder->first();

        if ($record)
            $reset_builder->update($data);
        else
            DB::table('password_reset_tokens')
                ->insert(
                    [
                        'email' => $data['email'],
                        'token' => $data['token'],
                        'created_at' => $data['created_at']
                    ]
                );

        Mail::to($data['email'])->send(new ResetPasswordCode($data['token']));

        return response()->json(['Message' => 'token sent']);
    }

    /**
     * Summary of checkToken
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkToken(Request $request)
    {
        $token = $request->validate([
            'token' => 'bail|required|string|exists:password_reset_tokens',
        ]);

        $reset_builder = DB::table('password_reset_tokens')->where('token', $token['token']);
        $record = $reset_builder->first();

        if ($record->created_at < now()->subHour()) {
            $reset_builder->delete();

            return response()->json(['Message' => 'Token is expired']);
        }

        return response()->json([
            'Message' => 'success',
            'token' => $token
        ]);
    }

    /**
     * Summary of resetPassword
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $token = $request->validate([
            'token' => 'bail|required|string|exists:password_reset_tokens',
        ]);

        $reset_builder = DB::table('password_reset_tokens')->where('token', $token['token']);
        $record = $reset_builder->first();

        $user = User::where('email', $record->email)->first();

        $user->update(['password' => bcrypt($request->password)]);

        $token = $user->createToken('Log in', [''], now()->addYear())->plainTextToken;

        $reset_builder->delete();

        return response()->json([
            'Message' => 'Password set Successfully',
            'token' => $token,
            'data' => []
        ]);
    }
}

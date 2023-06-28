<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Friend;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function sendRequest($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        $test = Friend::where('sender', $user->id)->where('reciever', $id)->value('id');
        if ($test == null) {
            $request_media = Friend::create([
                'sender' => $user->id,
                'receiver' => $id,
                'is_approved' => false,
            ]);
            return $request_media->save();
        } else
            return 'already sent';
    }

    public function accept(Request $request, $id)
    {
        // acccept/decline
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        $test = Friend::where('reciever', $user->id)->get();
        $sender = Friend::where('reciever', $user->id)->value('sender');
        Friend::where('sender', $id)->update(
            [
                'is_approved' => $request->is_approved,
            ]
        );
        return Friend::find($id);
    }

    public function getrequeststome(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        return $test = Friend::where('reciever', $user->id)->get();
    }

    public function getrequeststopeople(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        return $test = Friend::where('sender', $user->id)->get();
    }
}

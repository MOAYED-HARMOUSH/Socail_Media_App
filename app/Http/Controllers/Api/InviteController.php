<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function create(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        $test = Invite::where('sender_invite', $user->id)->where('reciever_invite', $id)->value('id');
        if ($test == null) {
            $request_media = Invite::create([
                'sender_invite' => $user->id,
                'receiver_invite' => $id,
                'page_invite_id' => $request->page_invite_id,
            ]);
            return $request_media->save();
        } else {
            return 'already invite him';
        }
    }

    public function getmyinvitestome()
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        return  $test = Invite::where('reciever_invite', $user->id)->get();
    }

    public function getmyinvitestopeople(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        //    return Request_Media::all();
        return  $test = Invite::where('sender_invite', $user->id)->get();
    }

    public function accept(Request $request, $id)
    {
        { // acccept/decline  // اعمل is approved وتحقق بالكويري
            $user = Auth::user();
            $user = User::find($user->id);
            $tosenderifacceptornot = $request->is_approved;
            if ($tosenderifacceptornot == 1) {
                // ارسال اشعار للمرسل بالقبول
            } else {
                // ارسال اشعار للمرسل بالرفض

            }
            $test = Invite::where('reciever_invite', $user->id)->get();
            $sender = Invite::where('reciever_invite', $user->id)->value('sender_invite');
            Invite::where('sender_invite', $id)->update(
                [
                    'is_approved' => $request->is_approved,
                ]
            );
            return Invite::find($id);
        }
    }

    public function index()
    {
        return Invite::all();
    }
}

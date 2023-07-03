<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InviteController extends Controller
{
    public function getFriendsNotMembers(Request $request, FriendController $friendController)
    {
        $friends = $friendController->showFriends($request);

        $not_members = [];
        foreach ($friends as $friend) {
            $is_admin = $friend->pages()->find($request->id);
            $is_member = $friend->memberPages()->find($request->id);

            if ($is_admin == null && $is_member == null)
                $not_members[] = $friend;
        }

        return response()->json([
            'Message' => 'success',
            'Not Members' => $not_members
        ]);
    }

    public function send(Request $request)
    {
        $request->user()->inviters()->attach($request->id, ['page_id' => $request->page_id]);

        //Send Notification to Receiver

        return response()->json(['Message' => 'Success']);
    }

    public function accept(Request $request)
    {
        $request->user()->invitees()
            ->where('invites.page_id', $request->id)
            ->update(['is_approved' => true]);

        FollowPageController::follow($request);

        //Send Notification to Sender

        return response()->json(['Message' => 'Success']);
    }
}

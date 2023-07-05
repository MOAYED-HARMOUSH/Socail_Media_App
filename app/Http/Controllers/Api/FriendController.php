<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->id == $request->id)
            return 'Don\'t be Silly';

        $received = $request->user()->receivers()->where('friends.sender', $request->id)->get();

        if (sizeof($received) == 0)
            $request->user()->senders()->attach($request->id);
        else
            return response()->json([
                'Message' => 'You have request from this user'
            ]);

        // TODO : Send Notification to Receiver

        return response()->json(['Message' => 'Success']);
    }

    public function cancel(Request $request)
    {
        $request->user()->senders()
            ->where('friends.receiver', $request->id)
            ->whereNull('friends.is_approved')
            ->detach($request->id);

        // TODO : Delete Notification From Database.
        // TODO : Cancel Notification to Receiver.

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function accept(Request $request)
    {
        $request->user()->receivers()
            ->where('friends.sender', $request->us_id)
            ->update(['is_approved' => true]);

        // TODO : Send Notification to Sender

        return response()->json(['Message' => 'Success']);
    }

    public function reject(Request $request)
    {
        $request->user()->receivers()
            ->where('friends.sender', $request->id)
            ->update(['is_approved' => false]);

        return response()->json(['Message' => 'Success']);
    }

    public function showRejectedRequests(Request $request)
    {
        $rejected_request = $request->user()->senders()->where('friends.is_approved', false)->get();

        return response()->json([
            'Message' => 'success',
            'Requests' => $rejected_request
        ]);
    }

    public function showRefusedRequests(Request $request)
    {
        $refused_request = $request->user()->receivers()->where('friends.is_approved', false)->get();

        return response()->json([
            'Message' => 'success',
            'Requests' => $refused_request
        ]);
    }

    public function showFriends(Request $request)
    {
        $received_friends = $request->user()->receivers()->where('friends.is_approved', true)->get();
        $sent_friends = $request->user()->senders()->where('friends.is_approved', true)->get();

        $friends = $received_friends->concat($sent_friends);

        return $friends;
    }

    public function returnFriends(Request $request)
    {
        return response()->json([
            'Message' => 'Success',
            'friends' => $this->showFriends($request)
        ]);
    }

    public function showReceiverRequest(Request $request)
    {
        $receiver_request = $request->user()->receivers()->whereNull('friends.is_approved')->get();

        //Delete Notification from Database

        return response()->json([
            'Message' => 'success',
            'Requests' => $receiver_request
        ]);
    }

    public function showSenderRequest(Request $request)
    {
        $sender_request = $request->user()->senders()->whereNull('friends.is_approved')->get();

        return response()->json([
            'Message' => 'success',
            'Requests' => $sender_request
        ]);
    }
}

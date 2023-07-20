<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Notifications\FriendRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FriendController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->id == $request->id)
            return 'You Can\'t Send Friend Request to yourself';

        $received = $request->user()->receivers()->where('friends.sender', $request->id)->first();

        if ($received == null) {
            $request->user()->senders()->attach($request->id);
            // TODO : Send Notification to Receiver

            $receiver = User::find($request->id);
            $receiver->notify(
                new FriendRequest(
                    true,
                    $request->user()->name
                )
            );
        } else
            return response()->json([
                'Message' => 'You have request from this user'
            ]);

        return response()->json(['Message' => 'Success']);
    }

    public function cancel(Request $request)
    {
        $request->user()->senders()
            ->where('friends.receiver', $request->id)
            ->whereNull('friends.is_approved')
            ->detach($request->id);

        // TODO : Delete Notification From Database.

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function accept(Request $request)
    {
        $request->user()->receivers()
            ->where('friends.sender', $request->id)
            ->update(['is_approved' => true]);

        // TODO : Send Notification to Sender

        $sender = User::find($request->id);
        $sender->notify(
            new FriendRequest(
                false,
                $request->user()->name
            )
        );

        return response()->json(['Message' => 'Success']);
    }

    public function reject(Request $request)
    {
        $request->user()->receivers()
            ->where('friends.sender', $request->id)
            ->update(['is_approved' => false]);

        return response()->json(['Message' => 'Success']);
    }

    public function showRefusedRequests(Request $request)
    {
        $refused_request = $request->user()->receivers()->where('friends.is_approved', false)->get();

        foreach ($refused_request as $value) {
            $value->setAppends(['period_receiver']);
        }

        return response()->json([
            'Message' => 'success',
            'Requests' => $refused_request
        ]);
    }

    public function showFriends(Request $request)
    {
        $received_friends = $request->user()->receivers()->where('friends.is_approved', true)->get();
        $sent_friends = $request->user()->senders()->where('friends.is_approved', true)->get();

        // foreach ($received_friends as $value) {
        //     $value->setAppends(['period_receiver']);
        // }

        // foreach ($sent_friends as $value) {
        //     $value->setAppends(['period_sender']);
        // }

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

        foreach ($receiver_request as $value) {
            $value->setAppends(['period_receiver']);
        }

        // TODO : Delete Notification from Database

        return response()->json([
            'Message' => 'success',
            'Requests' => $receiver_request
        ]);
    }

    public function showSenderRequest(Request $request)
    {
        $sender_request = $request->user()->senders()->whereNull('friends.is_approved')->get();

        foreach ($sender_request as $value) {
            $value->setAppends(['period_sender']);
        }

        return response()->json([
            'Message' => 'success',
            'Requests' => $sender_request
        ]);
    }
}

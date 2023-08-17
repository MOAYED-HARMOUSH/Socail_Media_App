<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\counterpost;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Summary of completeInfo
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function completeInfo(Request $request)
    {
        if ($request->has('study_semester'))
            $request->user()->student()->create($request->all());

        if ($request->has('companies'))
            $request->user()->expert()->create([
                'companies' => json_encode(explode(',', $request->companies)),
                'years_as_expert' => $request->years_as_expert,
                'work_at_company' => $request->work_at_company
            ]);

        $request->user()->update($request->all());

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function show(Request $request, int $id = null)
    {
        if (isset($id))
            return $this->showAnotherProfile($request, $id);
        else
            return $this->showMyProfile($request);
    }

    public function showAnotherProfile(Request $request, int $id)
    {
        $button2 = null;

        $user = User::find($request->id);
        if ($user == null)
            return response()->json([
                'Message' => 'Invalid id'
            ]);

        $url = $user->getFirstMedia('avatars')?->original_url;
        $user->student;
        $user->expert;
        $user->specialty;

        $receiver = $request->user()->senders()->where('friends.receiver', $id)->first();
        $sender = $request->user()->receivers()->where('friends.sender', $id)->first();

        if ($receiver != null) {
            $is_approved = $receiver->sender->is_approved;
            if (isset($is_approved))
                $button1 = ($is_approved == false) ? 'Rejected' : 'Accepted';
            else
                $button1 = 'Cancel Request';
        } elseif ($sender != null) {
            $is_approved = $sender->receiver->is_approved;
            if (isset($is_approved))
                $button1 = ($is_approved == true) ? 'Reject' : 'Accept';
            else {
                $button1 = 'Accept';
                $button2 = 'Reject';
            }
        } else
            $button1 = 'Send Friend Request';

        $user = collect($user)->except(['email', 'email_verified_at', 'created_at', 'updated_at']);
        return response()->json([
            'Message' => 'success',
            'user' => $user,
            'media_url' => $url,
            'button1' => $button1,
            'button2' => $button2
        ]);
    }

    public function showMyProfile(Request $request)
    {
        $user = $request->user();
        $url = $user->getFirstMedia('avatars')?->original_url;
        $user->student;
        $user->expert;
        $user->specialty;
        return response()->json([
            'Message' => 'success',
            'user' => $user,
            'media_url' => $url,
            'button1' => null,
            'button2' => null
        ]);
    }

    /**
     * Summary of edit
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('avatars');
        }

        $this->editSpecialty($request);

        if ($request->has('study_semester'))
            if ($user->student()->first() != null)
                $user->student()->first()->update($request->all());
            else
                $user->student()->create($request->all());

        if ($request->has('companies')) {
            if ($user->expert()->first() != null)
                $user->expert()->first()->update([
                    'companies' => json_encode(explode(',', $request->companies)),
                    'years_as_expert' => $request->years_as_expert,
                    'work_at_company' => $request->work_at_company
                ]);
            else
                $user->expert()->first()->create([
                    'companies' => json_encode(explode(',', $request->companies)),
                    'years_as_expert' => $request->years_as_expert,
                    'work_at_company' => $request->work_at_company
                ]);
        }

        $user->update($request->all());
        //ToDo: Verification Email Required when Changing Email

        return response()->json([
            'Message' => 'success'
        ]);
    }

    /**
     * Summary of editSpecialty
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function editSpecialty(Request $request)
    {
        $old_specialty = $request->user()->specialty()->first();
        $old_specialty_arr = array_merge(
            [$old_specialty->specialty],
            explode(',', $old_specialty->section),
            explode(',', $old_specialty->framework),
            explode(',', $old_specialty->language)
        );

        $old_specialty->update($request->all());

        $new_specialty_arr = array_merge(
            [$request->specialty],
            explode(',', $request->section),
            explode(',', $request->framework),
            explode(',', $request->language)
        );

        $new_specialties_arr = array_diff($new_specialty_arr, $old_specialty_arr);
        $old_specialties_arr = array_diff($old_specialty_arr, $new_specialty_arr);

        $new_specialty = implode(',', $new_specialties_arr);
        $old_specialty = implode(',', $old_specialties_arr);

        CommunityController::addUserToCommunity($new_specialty, $request->user());
        CommunityController::removeUserFromCommunity($old_specialty, $request->user());

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function createRandomUsers($count)
    {

        $factory = User::factory();

        for ($i = 0; $i < $count; $i++) {
            $user = $factory->create();
            $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;
            $arr[$i] = $token;
            $users[$i] = $user;
        }
        app()->make(\Database\Seeders\MainSeeder::class)->run();

        return $arr;
    }
    public function get_profile_posts(Request $request, $id)
    {

        $user = Auth::user();
        $user = User::find($user->id);
        $l = counterpost::where('location', 'profile')->where('user_id', $user->id)->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => 'profile'
            ]);
        } else {
            $count = counterpost::where('location', 'profile')->where('user_id', $user->id)->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('location', 'profile')->where('user_id', $user->id)->value('counter_post');


        $profile =   User::where('id', $id)->first();


        $posts_ids = Post::where('location_type', 'App\Models\User')->where('user_id', $id)->pluck('id');

        $posts_controller = new PostController;
        $posts = $posts_controller->ExtraInfo_Post($posts_ids, $user);

        $collection = collect($posts);

        $sorted_posts = $collection->sortByDesc('created_at');

        $valueall = [];
        foreach ($posts as  $value) {
            $po = $value[4];

            $valueall[] = $po;
        }

        $sorted_posts = collect($valueall)->sortByDesc('created_at');
        $so = $sorted_posts->pluck('id');

        $gg = $posts_controller->ExtraInfo_Post($so, $user);

        $first_fifteen = array_slice($gg, ($v - 1) * 15, 15);
        if ($first_fifteen == null) {
            $count = counterpost::where('location', 'profile')->where('user_id', $user->id)->delete();

            return 'end posts >> referssh';
        }
        return response()->json([
            'Message' => 'success',

            'data' => ['posts' => $first_fifteen],

        ]);
    }
    public function show_unread_notification(Request $request)
    {

        $notifications = $request->user()->unreadNotifications;

        foreach ($notifications as $notification) {
            $notification->read_at = Carbon::now();
            $notification->save();
        }

        $sortedNotifications = $notifications->sortByDesc('created_at');
        return response()->json([
            'message'=>'succes',
            'data'=>$sortedNotifications
        ]);
    }
    // return $not[7];


    public function show_old_notification(Request $request)
    {

        $notifications = $request->user()->readNotifications;
        $sortedNotifications = $notifications->sortByDesc('created_at');

        return response()->json([
            'message'=>'succes',
            'data'=>$sortedNotifications
        ]);
    }
    public function get_my_profile_posts(Request $request)
    {

        $user = Auth::user();
        $user = User::find($user->id);
        $l = counterpost::where('location', 'profile')->where('user_id', $user->id)->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => 'profile'
            ]);
        } else {
            $count = counterpost::where('location', 'profile')->where('user_id', $user->id)->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('location', 'profile')->where('user_id', $user->id)->value('counter_post');




        $posts_ids = Post::where('location_type', 'App\Models\User')->where('user_id', $user->id)->pluck('id');

        $posts_controller = new PostController;
        $posts = $posts_controller->ExtraInfo_Post($posts_ids, $user);

        $collection = collect($posts);

        $sorted_posts = $collection->sortByDesc('created_at');

        $valueall = [];
        foreach ($posts as  $value) {
            $po = $value[4];

            $valueall[] = $po;
        }

        $sorted_posts = collect($valueall)->sortByDesc('created_at');
        $so = $sorted_posts->pluck('id');

        $gg = $posts_controller->ExtraInfo_Post($so, $user);

        $first_fifteen = array_slice($gg, ($v - 1) * 15, 15);
        if ($first_fifteen == null) {
            $count = counterpost::where('location', 'profile')->where('user_id', $user->id)->delete();

            return 'end posts >> referssh';
        }
        return response()->json([
            'Message' => 'success',

            'data' => ['posts' => $first_fifteen],

        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\counterpost;

class CommunityController extends Controller
{
    /**
     * Summary of addUserToCommunity
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return void
     */
    public static function addUserToCommunity($request, User $user)
    {
        $communities_names = array_merge(
            [$request->specialty],
            explode(',', $request->section),
            explode(',', $request->framework),
            explode(',', $request->language)
        );
        $communities_names = array_map(fn ($element) => $element . ' Space', $communities_names);

        $communities = Community::whereIn('name', $communities_names)->get();
        $community_id = $communities->pluck('id')->toArray();

        $user->communities()->attach($community_id);

        foreach ($communities as $community) {
            $community->update(['subscriber_counts' => $community->subscriber_counts + 1]);
        }
    }

    public static function subSubscriberCounts(User $user)
    {
        $communities = $user->communities()->get();
        foreach ($communities as $community)
            $community->update(['subscriber_counts' => $community->subscriber_counts - 1]);
    }


    public function getcommunityInfo(Request $request, $id)
    {

        $user = Auth::user();
        $user = User::find($request->id);
 $type=$request->type;
        $l = counterpost::where('location', 'community')->where('user_id', $user->id)->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => 'community'
            ]);
        } else {
            $count = counterpost::where('location', 'community')->where('user_id', $user->id)->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('location', 'community')->where('user_id', $user->id)->value('counter_post');


        $community =   Community::where('id', $id)->first();
        $me = collect(Media::where('collection_name', 'communities_photos')->where('model_id', $id)->get())->map(function ($media) {
            $fullPath = str_replace('\\', '/', $media->getUrl());
            $publicPath = Str::after($fullPath, 'http://127.0.0.1:8000/');
            return $publicPath;
        })->all();

        if ($type =='all') {
            $posts_ids = Post::where('location_type', 'App\Models\Community')->where('location_id', $id)->pluck('id');
        } else {
             $posts_ids = Post::where('location_type', 'App\Models\Community')->where('location_id', $id)->where('type', $type)->pluck('id');
        }

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
            $count = counterpost::where('location', 'community')->where('user_id', $user->id)->delete();

            return 'end posts >> referssh';
        }
        return response()->json([
            'Message' => 'success',
            'photo' => $me,
            'info' => $community,
            'data' => ['posts' => $first_fifteen],

        ]);
    }
    public function Add_Community_Photo(Request $request)
    {
        $id = $request->id;
        $communitiy = Community::where('id', $id)->first();

        $photo = $communitiy->addMediaFromRequest('image')->toMediaCollection('communities_photos');
    }
}

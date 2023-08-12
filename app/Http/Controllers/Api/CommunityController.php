<?php

namespace App\Http\Controllers\Api;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\counterpost;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Summary of addUserToCommunity
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return void
     */
    public static function addUserToCommunity(string|Request|Specialty $source, User $user): void
    {
        $communities = static::getAppropriateCommunities($source);
        $community_id = $communities->pluck('id')->toArray();

        $user->communities()->attach($community_id);

        static::addSubscriberCounts($communities);
    }

    /**
     * Summary of removeUserFromCommunity
     * @param string|\Illuminate\Http\Request|\App\Models\Specialty $source
     * @param \App\Models\User $user
     * @return void
     */
    public static function removeUserFromCommunity(string|Request|Specialty $source, User $user): void
    {
        $communities = static::getAppropriateCommunities($source);
        $community_id = $communities->pluck('id')->toArray();

        $user->communities()->detach($community_id);

        static::subSubscriberCounts($communities);
    }

    /**
     * Summary of getAppropriateCommunities
     * @param string|\Illuminate\Http\Request|\App\Models\Specialty $source
     * @return mixed
     */
    public static function getAppropriateCommunities(string|Request|Specialty $source)
    {
        if (gettype($source) == 'string')
            $communities_names = explode(',', $source);
        else {
            $communities_names = array_merge(
                [$source->specialty],
                explode(',', $source->section),
                explode(',', $source->framework),
                explode(',', $source->language)
            );
        }
        $communities_names = array_map(fn($element) => $element . ' Space', $communities_names);

        return Community::whereIn('name', $communities_names)->get();
    }

    /**
     * Summary of subSubscriberCounts
     * @param mixed $communities
     * @return void
     */
    public static function subSubscriberCounts($communities)
    {
        foreach ($communities as $community)
            $community->update(['subscriber_counts' => $community->subscriber_counts - 1]);
    }

    /**
     * Summary of addSubscriberCounts
     * @param mixed $communities
     * @return void
     */
    public static function addSubscriberCounts($communities)
    {
        foreach ($communities as $community) {
            $community->update(['subscriber_counts' => $community->subscriber_counts + 1]);
        }
    }



    public function Add_Community_Photo(Request $request)
    {
        $id = $request->id;
        $communitiy = Community::where('id', $id)->first();

        $photo = $communitiy->addMediaFromRequest('image')->toMediaCollection('communities_photos');
    }
}

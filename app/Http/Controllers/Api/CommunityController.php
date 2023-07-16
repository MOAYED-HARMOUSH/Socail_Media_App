<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use Illuminate\Http\Request;

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
        $communities_names = array_map(fn($element) => $element . ' Space', $communities_names);

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
}

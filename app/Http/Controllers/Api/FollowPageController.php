<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowPageController extends Controller
{
    public static function follow(Request $request)
    {
        $request->user()->memberPages()->attach($request->id);

        $page = Page::find($request->id);
        $page->update(['follower_counts' => $page->follower_counts + 1]);

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function unfollow(Request $request)
    {
        $request->user()->memberPages()->where('id', $request->id)->detach($request->id);

        $page = Page::find($request->id);
        $page->update(['follower_counts' => $page->follower_counts - 1]);

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function show()
    {
        $user = Auth::user();
        $user2 = User::find($user->id);

        return $user2->Follow_Page()->get();
        //    $a=$user2->Follow_Page()->pluck('id');
        //    return $pages = Page::whereIn('id', $a)->get();
    }
}

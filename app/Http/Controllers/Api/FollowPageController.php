<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public static function index(Request $request)
    {
        $my_followed_pages = $request->user()->memberPages()->get();

        foreach ($my_followed_pages as $page) {
            $page->getFirstMedia('cover_image');
            $page->getFirstMedia('main_image');
        }

        return response()->json([
            'Message' => 'success',
            'My Followed Pages' => $my_followed_pages
        ]);
    }
}

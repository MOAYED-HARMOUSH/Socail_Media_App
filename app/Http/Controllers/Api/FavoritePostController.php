<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoritePostController extends Controller
{
    public function create(Request $request)
    {
        $request->user()->favoritePosts()->attach($request->id);

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function show(Request $request, PostController $postController)
    {
        $saved_posts = $request->user()->favoritePosts()->pluck('id');
        // $saved_posts->pluck('id');
        return $postController->ExtraInfo_Post($saved_posts, $request->user());
    }

    public function destroy(Request $request)
    {
        $request->user()->favoritePosts()->detach($request->id);

        return response()->json([
            'Message' => 'success'
        ]);
    }
}

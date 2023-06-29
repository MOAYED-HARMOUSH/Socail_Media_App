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

    public function show(Request $request)
    {
        $saved_posts = $request->user()->favoritePosts()->get();

        return response()->json([
            'Message' => 'success',
            'saved posts' => $saved_posts
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->favoritePosts()->detach($request->id);

        return response()->json([
            'Message' => 'success'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\counterpost;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public static function subMemberCounts(User $user)
    {
        $pages = $user->memberPages()->get();
        foreach ($pages as $page)
            $page->update(['follower_counts' => $page->follower_counts - 1]);
    }

    public function index(Request $request)
    {
        $my_owned_pages = $request->user()->pages()->get();

        foreach ($my_owned_pages as $page) {
            $page->getFirstMedia('cover_image');
            $page->getFirstMedia('main_image');
        }
        return response()->json([
            'Message' => 'success',
            'Pages' => $my_owned_pages,
        ]);
    }

    public function create(Request $request)
    {
        // $request->validate([
        //     'email' => 'bail|required|email',
        //     'bio' => 'bail|required|string|max:100',
        //     'cover_image' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
        //     'main_image' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
        //     'type' => 'bail|required|in:Company,Famous,Specialty',
        //     'name' => 'bail|required|string',
        // ]);

        $page = $request->user()->pages()->create($request->all());

        if ($request->hasFile('main_image')) {
            $page->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        if ($request->hasFile('cover_image')) {
           $image= $page->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        return response()->json([
            'Message' => 'success',
            'page' => $page,
            'image'=>$image->getUrl()
        ]);
    }

    public function show(Request $request)
    {
        // TODO: Get Posts From Page
        $my_own_page = $request->user()->pages()->find($request->id);
        if ($my_own_page != null)

            return response()->json([
                'Message' => 'success',
                'Page' => $my_own_page,
                'Role' => 'Admin',
                // 'Posts' => $posts
            ]);

        $my_followed_page = $request->user()->memberPages()->find($request->id);
        if ($my_followed_page != null)
            return response()->json([
                'Message' => 'success',
                'Page' => $my_followed_page,
                'Role' => 'Member',
                // 'Posts' => $posts
            ]);

        $page = Page::find($request->id);
        return response()->json([
            'Message' => 'success',
            'Page' => $page,
            'Role' => 'Visiter'
            // 'Posts' => $posts
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->pages()->find($request->id)->delete();

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function edit(Request $request)
    {
        $page = $request->user()->pages()->find($request->id);

        if ($request->hasFile('main_image')) {
            $page->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        if ($request->hasFile('cover_image')) {
            $page->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        $page->update($request->all());

        return response()->json([
            'Message' => 'success',
            'Page' => $page
        ]);
    }
    public function get_page_posts(Request $request, $id)
    {

        $user = Auth::user();
        $user = User::find($user->id);

        $l = counterpost::where('location', 'page')->where('user_id', $user->id)->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => 'page'
            ]);
        } else {
            $count = counterpost::where('location', 'page')->where('user_id', $user->id)->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('location', 'page')->where('user_id', $user->id)->value('counter_post');


        $page =   Page::where('id', $id)->first();
        $me = collect(Media::where('collection_name', 'cover_image')->where('model_id', $id)->get())->map(function ($media) {
            $fullPath = str_replace('\\', '/', $media->getUrl());
            $publicPath = Str::after($fullPath, 'http://127.0.0.1:8000/');
            return $publicPath;
        })->all();

        $posts_ids = Post::where('location_type', 'App\Models\Page')->where('location_id', $id)->pluck('id');

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
            $count = counterpost::where('location', 'page')->where('user_id', $user->id)->delete();

            return  response()->json([
                'Message' => 'success',
                'info' => $page,
                'photo' => $me,
                'data' => ['posts' => []],

            ]);;
        }
        return response()->json([
            'Message' => 'success',
            'info' => $page,
            'photo' => $me,
            'data' => ['posts' => $first_fifteen],

        ]);
    }
}

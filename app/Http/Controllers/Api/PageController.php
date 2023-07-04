<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            $page->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        return response()->json([
            'Message' => 'success',
            'page' => $page
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
                'Status' => 'Admin',
                // 'Posts' => $posts
            ]);

        $my_followed_page = $request->user()->memberPages()->find($request->id);
        if ($my_followed_page != null)
            return response()->json([
                'Message' => 'success',
                'Page' => $my_followed_page,
                'Status' => 'Member',
                // 'Posts' => $posts
            ]);

        $page = Page::find($request->id);
        return response()->json([
            'Message' => 'success',
            'Page' => $page,
            'Status' => 'Visiter'
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
            'Page' => collect($page)->except('media')
        ]);
    }
}

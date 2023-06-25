<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
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
        //show the specific one with its posts
    }

    public function destroy(Request $request)
    {
        $request->user()->pages()->find($request->id)->delete();

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function edit(Request $request, $id)
    {
        //
    }
}

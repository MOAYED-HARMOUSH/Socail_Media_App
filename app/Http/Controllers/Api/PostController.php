<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PostController extends Controller
{
    public function create_from_profile(Request $request)
    { // on home page

        $user = Auth::user();
        $user = User::find($user->id);


        $image = $request->image;
        $image2 = $request->image2;
        $image3 = $request->image3;
        $image4 = $request->image4;

        $vedio = $request->vedio;
        $vedio2 = $request->vedio2;
        $vedio3 = $request->vedio3;
        $vedio4 = $request->vedio4;


        $create = $user->locationPosts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'user_id' => $user->id
        ]);

        if ($image != null && $request->hasFile('image')) {

            $ph1 = $user->addMediaFromRequest('image')->toMediaCollection('post_photos');

            $post_photos = $user->getFirstMedia('post_photos');


            $create->photos()->create([
                'media_id' => $ph1->id
            ]);
        }
        if ($image2 != null && $request->hasFile('image2')) {

            $ph2 = $user->addMediaFromRequest('image2')->toMediaCollection('post_photos');
            $post_photos = $user->getFirstMedia('post_photos');


            $create->photos()->create([
                'media_id' => $ph2->id
            ]);
        }
        if ($image3 != null && $request->hasFile('image3')) {

            $ph3 =  $user->addMediaFromRequest('image3')->toMediaCollection('post_photos');
            $post_photos = $user->getFirstMedia('post_photos');


            $create->photos()->create([
                'media_id' => $ph3->id
            ]);
        }
        if ($image4 != null && $request->hasFile('image4')) {

            $ph4 =  $user->addMediaFromRequest('image4')->toMediaCollection('post_photos');
            $post_photos = $user->getFirstMedia('post_photos');


            $create->photos()->create([
                'media_id' => $ph4->id
            ]);
        }
        if ($vedio != null && $request->hasFile('vedio')) {
            $v1 =   $user->addMediaFromRequest('vedio')->toMediaCollection('post_vedios');
            $post_vedios = $user->getFirstMedia('post_vedios');

            $create->videos()->create([
                'media_id' => $v1->id
            ]);
        }

        if ($vedio2 != null && $request->hasFile('vedio2')) {
            $v2 =  $user->addMediaFromRequest('vedio2')->toMediaCollection('post_vedios');
            $post_vedios = $user->getFirstMedia('post_vedios');

            $create->videos()->create([
                'media_id' => $v2->id
            ]);
        }
        if ($vedio3 != null && $request->hasFile('vedio3')) {
            $v3 =   $user->addMediaFromRequest('vedio3')->toMediaCollection('post_vedios');
            $post_vedios = $user->getFirstMedia('post_vedios');

            $create->videos()->create([
                'media_id' => $v3->id
            ]);
        }
        if ($vedio4 != null && $request->hasFile('vedio4')) {
            $v4 =  $user->addMediaFromRequest('vedio4')->toMediaCollection('post_vedios');
            $post_vedios = $user->getFirstMedia('post_vedios');

            $create->videos()->create([
                'media_id' => $v4->id
            ]);
        }
        $message = 'created ';
        return response()->json($create, 200, [$message]);
    }
    public function getPost($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $post_time =  $post = $user->posts()->where('id', $id)->first()->created_at;

        $old_datetime = Carbon::parse($post_time)->subHours(48)->format('Y-m-d H:i');
        $day_name = date('l', strtotime($post_time));

        $now = Carbon::now();


        if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 48) {
            $diff = 'yestarday at : ' . Carbon::parse($post_time)->format(' h:i A');
        } else if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 168) {
            $diff = $day_name . 'at' .  Carbon::parse($post_time)->format(' h:i A');
        } else if ($now->diffInHours($old_datetime) > 24) {
            $diff = Carbon::parse($post_time)->format('Y-m-d h:i A');
        } else if ($now->diffInMinutes($old_datetime) < 60) {
            $diff = $now->diffInMinutes($old_datetime) . ' minutes ago';
        } else {
            $diff = $now->diffInHours($old_datetime) . ' hours ago';
        }
        $poster = $user->first_name . ' ' . $user->last_name;

        $post = $user->posts()->where('id', $id)->first();

        $photos_media = $post->photos()->where('post_id', $id)->pluck('media_id');

        $videos_media = $post->videos()->where('post_id', $id)->pluck('media_id');

        $arr = $photos_media->merge($videos_media);


        $me = collect(Media::whereIn('id', $arr)->get());

        return array_merge([$poster], [$diff], [$post], [$me]);
    }
    // public function getallposts() // just   test
    // {
    //     return Post::all();
    // }
}

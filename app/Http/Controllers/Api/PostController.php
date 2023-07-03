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
use App\Models\Community;
use App\Models\CommunityUser;

class PostController extends Controller
{

    public function create_from_profile(Request $request)
    { // on home page

        $user = Auth::user();
        $user = User::find($user->id);



        $post = $user->locationPosts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'user_id' => $user->id
        ]);

        $this->medi($request, $post, $user);
        return response()->json($post, 200, ['created']);
    }
    public function create_from_community(Request $request, $id)
    { // on home page

        $user = Auth::user();
        $user = User::find($user->id);
        $community = Community::find($id);
        $post = $community->posts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'user_id' => $user->id
        ]);

        $this->medi($request, $post, $user);
        return response()->json($post, 200, ['created']);
    }
    public function getMyPosts()
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $ids = $user->posts()->pluck('id');
        $bigarray = array(); // تعريف المتغير $bigarray

        foreach ($ids as  $value) {


            $post_time = Post::where('id', $value)->first()->created_at;

            $post = $user->posts()->where('id', $value)->first();


            $poster_degree1 = $post->user->student()->pluck('study_semester')->first();

            $poster_degree2 = $post->user->expert()->pluck('years_as_expert')->first();

            if (empty($poster_degree1) && empty($poster_degree2)) {
                $poster_degree = 'amateur';
            } else if (!empty($poster_degree1) && empty($poster_degree2)) {
                $poster_degree = $poster_degree1;
            } else if (empty($poster_degree1) && !empty($poster_degree2)) {
                $poster_degree = $poster_degree2;
            } else if (!empty($poster_degree1) && !empty($poster_degree2))
                $poster_degree = $poster_degree1 . '_ ' . 'years_as_expert = ' . $poster_degree2;

            $old_datetime = Carbon::parse($post_time)->format('Y-m-d H:i');
            $day_name = date('l', strtotime($post_time));

            $now = Carbon::now();


            if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 48) {
                $diff = 'yestarday at : ' . Carbon::parse($post_time)->format(' h:i A');
            } else if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 168) {
                $diff = $day_name . ' at :' .  Carbon::parse($post_time)->format(' h:i A');
            } else if ($now->diffInHours($old_datetime) > 24) {
                $diff = Carbon::parse($old_datetime)->format('Y-m-d h:i A');
            } else if ($now->diffInMinutes($old_datetime) < 60) {
                $diff = $now->diffInMinutes($old_datetime) . ' minutes ago';
            } else {
                $diff = $now->diffInHours($old_datetime) . ' hours ago';
            }
            $poster = $user->first_name . ' ' . $user->last_name;


            $photos_media = $post->photos()->where('post_id', $value)->pluck('media_id');

            $videos_media = $post->videos()->where('post_id', $value)->pluck('media_id');

            $arr = $photos_media->merge($videos_media);

            $me = collect(Media::whereIn('id', $arr)->get())->map(function ($media) {
                return $media->getUrl();
            })->all();

            $myArray = array_merge([$poster], [$poster_degree], [$diff], [$post], [$me]);

            $bigarray[$value] = $myArray;
        }

        return ($bigarray != null) ? $bigarray : 'you dont have posts';
    }

    public function getallposts()
    {
        return Post::all();
    }
    public function getMyCommuites()
    {
        $user = Auth::user();
        $user = User::find($user->id)->id;
        $sub = CommunityUser::where('user_id', $user)->pluck('community_id');
        return Community::whereIn('id', $sub)->get();
    }

    public function medi($request, $post, $user)
    {
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile('image' . $i)) {
                $request->hasFile('image' . $i);
                $photo = $user->addMediaFromRequest('image' . $i)->toMediaCollection('post_photos');
                $post->photos()->create([
                    'media_id' => $photo->id
                ]);
            }

            if ($request->hasFile('video' . $i)) {
                $request->hasFile('video' . $i);

                $video = $user->addMediaFromRequest('video' . $i)->toMediaCollection('post_vedios');
                $post->videos()->create([
                    'media_id' => $video->id
                ]);
            }
        }
        $message = 'created ';
    }
    public function gethomeposts()
    {

        $user = Auth::user();
        $user = User::find($user->id);

       $aa= $user->communities;

//return $aa->posts;
       $a= ($this->getMyCommuites())->pluck('id');
 return $c=  post::where('location_type','App\Models\Community  ')->whereIn('location_id',$a)
 ->latest()->take(3)->get();
 return $c->posts();
    }
}

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
use App\Http\Controllers\Api\FriendController;
use App\Models\Comment;
use App\Models\Page;
use App\Models\PageUser;
use App\Models\Reaction;

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
    {

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
    public function create_from_page(Request $request, $id)
    {



        $user = Auth::user();
        $user = User::find($user->id);


        $page = Page::find($id);
        $check = Page::where('admin_id', $user->id)->where('id', $id)->value('id');
        if ($check == null) {
            return 'not admin';
        } else {
            $post = $page->posts()->create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'user_id' => $user->id
            ]);

            $this->medi($request, $post, $user);
            return response()->json($post, 200, ['created']);
        }
    }
    public function getMyPosts()
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $ids = $user->posts()->pluck('id');

        return  $this->ExtraInfo_Post($ids, $user);
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
    public function getMyPagesasfollow()
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $pages_id = $user->memberPages->pluck('id');
        return Page::whereIn('id', $pages_id)->get();
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

    public function gethomeposts(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $communites_ids = ($this->getMyCommuites())->pluck('id');
        $communites_posts_ids =  post::where('location_type', 'App\Models\Community')
            ->whereIn('location_id', $communites_ids)->pluck('id');

        $friend = new FriendController;
        $friends_ids = $friend->showFriends($request)->pluck('id');

        $friend_posts_ids = Post::where('location_type', 'App\Models\User')
            ->whereIn('user_id', $friends_ids)->pluck('id');

        $pages_ids = ($this->getMyPagesasfollow())->pluck('id');
        $pages_posts_ids =  post::where('location_type', 'App\Models\Page')
            ->whereIn('location_id', $pages_ids)->pluck('id');



        $all_posts = $communites_posts_ids->toArray();

        foreach ($friend_posts_ids as $value) {
            array_push($all_posts, $value);
        }
        foreach ($pages_posts_ids as $value2) {
            array_push($all_posts, $value2);
        }


        return  $this->ExtraInfo_Post($all_posts, $user);
    }

    public function ExtraInfo_Post($ids, $user)
    {
        $bigarray = array();

        foreach ($ids as  $value) {


            $post_time = Post::where('id', $value)->first()->created_at;


            $post = post::where('id', $value)->first();
            $myreaction_on_this =  Reaction::where('location_type', 'App\Models\Post')
                ->where('location_id', $value)->where('user_id', $user->id)->value('type');

            if ($myreaction_on_this != null) {
                $my_reacion = 'my _reaction_on_this_post is ' . $myreaction_on_this;
            } else
                $my_reacion = 'you have no reaction on this post ';
            $t = $post->user;

            $poster_degree1 = $t->student()->pluck('study_semester')->first();

            $poster_degree2 = $t->expert()->pluck('years_as_expert')->first();

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
            $poster = $post->user->first_name . ' ' . $post->user->last_name;


            $photos_media = $post->photos()->where('post_id', $value)->pluck('media_id');

            $videos_media = $post->videos()->where('post_id', $value)->pluck('media_id');

            $arr = $photos_media->merge($videos_media);

            $me = collect(Media::whereIn('id', $arr)->get())->map(function ($media) {
                return $media->getUrl();
            })->all();


            $myArray = array_merge([$poster], [$poster_degree], [$diff], [$post], [$me], [$my_reacion]);

            $bigarray[$value] = $myArray;
        }

        return ($bigarray != null) ? $bigarray : 'you dont have posts';
    }
    public function like_or_cancellike_on_post($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $post = Post::find($id);
        $post_react = Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->value('type');
        $dislikes_on_this = Post::where('id', $post->id)->value('dislikes_counts');

        $likes_on_this = Post::where('id', $post->id)->value('likes_counts');

        if ($post_react == 'like') {
            $post->update(['likes_counts' => $likes_on_this - 1]);

            Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_like';
        } else if ($post_react == 'dislikes') {
            Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->delete();

            $post->update(['dislikes_counts' => $dislikes_on_this - 1]);
            $post->update(['likes_counts' => $likes_on_this + 1]);
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
        } else {
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
            $post->update(['likes_counts' => $likes_on_this + 1]);
            return 'like';
        }
    }
    public function dislike_or_canceldislike_on_post($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $post = Post::find($id);
        $post_react = Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->value('type');
        $dislikes_on_this = Post::where('id', $post->id)->value('dislikes_counts');
        $likes_on_this = Post::where('id', $post->id)->value('likes_counts');


        if ($post_react == 'dislikes') {
            $post->update(['dislikes_counts' => $dislikes_on_this - 1]);

            Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_dislikes';
        } else if ($post_react == 'like') {
            Reaction::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->delete();

            $post->update(['likes_counts' => $likes_on_this - 1]);
            $post->update(['dislikes_counts' => $dislikes_on_this + 1]);
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
        } else {
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
            $post->update(['dislikes_counts' => $dislikes_on_this + 1]);
            return 'dislikes';
        }
    }
    public function create_comment_on_post(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        return $user->comments()->create([
            'content' => $request->content,
            'post_id' => $id,
        ]);
    }
    public function get_comments_on_post($id)
    {

        $post = Post::where('id', $id)->first();
        return $post->comments;
    }

    public function like_or_cancellike_on_comment($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $comment = Comment::find($id);

        $comment_react = Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->value('type');
        $dislikes_on_this = Comment::where('id', $comment->id)->value('dislikes_counts');

        $likes_on_this = Comment::where('id', $comment->id)->value('likes_counts');

        if ($comment_react == 'like') {
            $comment->update(['likes_counts' => $likes_on_this - 1]);

            Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_like';
        } else if ($comment_react == 'dislikes') {
            Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->delete();

            $comment->update(['dislikes_counts' => $dislikes_on_this - 1]);
            $comment->update(['likes_counts' => $likes_on_this + 1]);
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
        } else {
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
            $comment->update(['likes_counts' => $likes_on_this + 1]);
            return 'like';
        }
    }
    public function dislike_or_canceldislike_on_comment($id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $comment = Comment::find($id);
        $comment_react = Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->value('type');
        $dislikes_on_this = Comment::where('id', $comment->id)->value('dislikes_counts');
        $likes_on_this = Comment::where('id', $comment->id)->value('likes_counts');


        if ($comment_react == 'dislikes') {
            $comment->update(['dislikes_counts' => $dislikes_on_this - 1]);

            Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_dislikes';
        } else if ($comment_react == 'like') {
            Reaction::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->delete();

            $comment->update(['likes_counts' => $likes_on_this - 1]);
            $comment->update(['dislikes_counts' => $dislikes_on_this + 1]);
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
        } else {
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
            $comment->update(['dislikes_counts' => $dislikes_on_this + 1]);
            return 'dislikes';
        }
    }
}

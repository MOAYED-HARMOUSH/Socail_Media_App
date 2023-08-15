<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Community;
use App\Models\CommunityUser;
use App\Http\Controllers\Api\FriendController;
use App\Models\Agree;
use App\Models\Comment;
use App\Models\counterpost;
use App\Models\Expert;
use App\Models\Page;
use App\Models\Reaction;
use App\Models\Report;
use App\Models\SharePost;
// use App\Notifications\Comment;
use Illuminate\Support\Str;


class PostController extends Controller
{



    public function create_from_profile(Request $request)
    { // on home page

        $user = Auth::user();
        $user = User::find($user->id);

        $type = $request->type;
        if ($type == 'Challenge') {
            $type = 'Accepted Challenge';
        } else if ($type != 'Challenge') {
            $type = $request->type;
        }


        $post = $user->locationPosts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $type,
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

        $type = $request->type;
        if ($type == 'Challenge') {
            $type = 'Accepted Challenge';
        } else if ($type != 'Challenge') {
            $type = $request->type;
        }


        $page = Page::find($id);
        $check = Page::where('admin_id', $user->id)->where('id', $id)->value('id');
        if ($check == null) {
            return 'not admin';
        } else {
            $post = $page->posts()->create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $type,
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
        return   $posts = Post::all();
        $collection = collect($posts);

        // ترتيب المنشورات بناءً على 'created_at' بشكل تنازلي
        return   $sorted_posts = $collection->sortByDesc('created_at');
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

    public  function gethomeposts(Request $request, $ids = [])
    {

        $user = Auth::user();
        $user = User::find($user->id);

        $l = counterpost::where('user_id', $user->id)->where('location', 'homepage')->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => 'homepage'
            ]);
        } else {
            $count = counterpost::where('user_id', $user->id)->where('location', 'homepage')->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('user_id', $user->id)->where('location', 'homepage')->value('counter_post');


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

        $posts = $this->ExtraInfo_Post($all_posts, $user);
        $collection = collect($posts);

        $sorted_posts = $collection->sortByDesc('created_at');

        $valueall = [];
        foreach ($posts as  $value) {
            $po = $value[4];

            $valueall[] = $po;
        }

        $sorted_posts = collect($valueall)->sortByDesc('created_at');
        $so = $sorted_posts->pluck('id');

        $gg = $this->ExtraInfo_Post($so, $user);

        $first_fifteen = array_slice($gg, ($v - 1) * 15, 15);
        if ($first_fifteen == null) {
            $count = counterpost::where('user_id', $user->id)->where('location', 'homepage')->delete();

            return 'end posts >> referssh';
            //      //$l=0;
            //    return $this->gethomeposts($request );
        }
        return response()->json([
            'Message' => 'success',
            'data' => ['posts' => $first_fifteen]
        ]);
    }

    public function ExtraInfo_Post($ids, $user, $ifstories = null)
    {
        $bigarray = array();

        $user_degree = $user->expert()->value('years_as_expert');

        foreach ($ids as  $value) {
            $post_type = Post::where('id', $value)->first()->type;


            if (($post_type != 'Story' && ($user_degree == null && $post_type != 'Challenge'))
                || ($post_type != 'Story' && $user_degree != null) || $ifstories == 'story'
            ) {

                $post_time = Post::where('id', $value)->first()->created_at;


                $post = post::where('id', $value)->first();

                $share = SharePost::where('current_post', $post->id)->value('shared_post');

                $shared_post = Post::where('id', $share)->first();
                if ($shared_post != null) {
                    $shared_post_user = $shared_post->user;
                    $shared =  $this->ExtraInfo_Post([$share], $shared_post_user);
                } else {
                    $shared = null;
                }

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

                $poster_id =  $post->user->id;
                $poster_photo = collect(Media::where('model_id', $poster_id)->where('collection_name', 'avatars')->get())->map(function ($media) {
                    return $media->getUrl();
                })->first();

                $photos_media = $post->photos()->where('post_id', $value)->pluck('media_id');

                $videos_media = $post->videos()->where('post_id', $value)->pluck('media_id');

                $arr = $photos_media->merge($videos_media);

                $me = collect(Media::whereIn('id', $arr)->get())->map(function ($media) {
                    $fullPath = str_replace('\\', '/', $media->getUrl());
                    $publicPath = Str::after($fullPath, 'http://127.0.0.1:8000/');
                    return $publicPath;
                })->all();

                $myArray = array_merge([$poster], [$poster_photo], [$poster_degree], [$diff], [$post], [$me], [$my_reacion], [$shared]);

                $bigarray[$value] = $myArray;
            }
            //    }
            //  }
        }

        return array_values($bigarray);
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

            $id = $post->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(new \App\Notifications\Reaction(
                $user->name,
                'like',
                'post',
                $post->content
            ));
        } else {
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
            $post->update(['likes_counts' => $likes_on_this + 1]);

            $id = $post->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(new \App\Notifications\Reaction(
                $user->name,
                'like',
                'post',
                $post->content
            ));

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

            $id = $post->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'dislike',
                    'post',
                    $post->content
                )
            );
        } else {
            $post->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
            $post->update(['dislikes_counts' => $dislikes_on_this + 1]);

            $id = $post->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'dislike',
                    'post',
                    $post->content
                )
            );

            return 'dislikes';
        }
    }
    public function create_comment_on_post(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $post = Post::find($id);
        $id = $post->user()->get('id');
        $user_owner = User::find($id)->first();
        $user_owner->notify(new \App\Notifications\Comment(
            $user->name,
            'post',
            $post->content
        ));

        return $user->comments()->create([
            'content' => $request->content,
            'post_id' => $id,
        ]);
    }
    public function get_comments_on_post($id)
    {

        $post = Post::where('id', $id)->first();
        // foreach ($p as $key => $value) {
        //     # code...
        // }
        $name =
            $array = [];
        foreach ($post->comments as $key) {
            $poster = $key->user->first_name . ' ' . $key->user->last_name;
            $comment_time = $key->created_at;

            $old_datetime = Carbon::parse($comment_time)->format('Y-m-d H:i');
            $day_name = date('l', strtotime($comment_time));

            $now = Carbon::now();


            if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 48) {
                $diff = 'yestarday at : ' . Carbon::parse($comment_time)->format(' h:i A');
            } else if ($now->diffInHours($old_datetime) > 24 && $now->diffInHours($old_datetime) < 168) {
                $diff = $day_name . ' at :' .  Carbon::parse($comment_time)->format(' h:i A');
            } else if ($now->diffInHours($old_datetime) > 24) {
                $diff = Carbon::parse($old_datetime)->format('Y-m-d h:i A');
            } else if ($now->diffInMinutes($old_datetime) < 60) {
                $diff = $now->diffInMinutes($old_datetime) . ' minutes ago';
            } else {
                $diff = $now->diffInHours($old_datetime) . ' hours ago';
            }

            $myreaction_on_this =  Reaction::where('location_type', 'App\Models\Comment')
            ->where('location_id', $key->id)->where('user_id', $key->user->id)->value('type');

        if ($myreaction_on_this != null) {
            $my_reacion = 'my _reaction_on_this_post is ' . $myreaction_on_this;
        } else
            $my_reacion = 'you have no reaction on this post ';
            $array[] = [
                'commenter' => $poster,
                'time' => $diff,
                'comment' => $key,
                'my_reaction'=>$my_reacion
            ];


        }
            return response()->json([
                'Message' => 'success',
                'data' => $array
            ]);

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

            $id = $comment->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'like',
                    'comment',
                    $comment->content
                )
            );
        } else {
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'like'
            ]);
            $comment->update(['likes_counts' => $likes_on_this + 1]);

            $id = $comment->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'like',
                    'comment',
                    $comment->content
                )
            );

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

            $id = $comment->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'dislike',
                    'comment',
                    $comment->content
                )
            );
        } else {
            $comment->reactions()->create([
                'user_id' => $user->id,
                'type' => 'dislikes'
            ]);
            $comment->update(['dislikes_counts' => $dislikes_on_this + 1]);

            $id = $comment->user()->get('id');
            $user_owner = User::find($id)->first();
            $user_owner->notify(
                new \App\Notifications\Reaction(
                    $user->name,
                    'dislike',
                    'comment',
                    $comment->content
                )
            );

            return 'dislikes';
        }
    }
    public function report_or_cancelreport_on_post(Request $request, $id)
    {

        $user = Auth::user();
        $user = User::find($user->id);
        $post = Post::find($id);
        $location_type = $post->location_type;
        $location_id = $post->location_id;
        $post_report = Report::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->value('id');
        $reports_on_this = Post::where('id', $post->id)->value('reports_number');

        $range = 0;

        if ($location_type == 'App\Models\Community') {
            $subscribers =  Community::where('id', $location_id)->value('subscriber_counts');
            $range = round($subscribers / 10);
        } else if ($location_type == 'App\Models\Page') {

            $followes_counts =  Page::where('id', $location_id)->value('follower_counts');
            $range = round($followes_counts / 20);
        } else if ($location_type == 'App\Models\User') {
            $friend = new FriendController;
            $friends_ids = $friend->showFriends($request)->count();


            $range = round($friends_ids / 30);
        }
        $halfrange = round($range) / 2;






        if ($post_report != null) {
            $post->update(['reports_number' => $reports_on_this - 1]);

            Report::where('location_type', 'App\Models\Post')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_report';
        } else if ($post_report == null) {
            $post->reports()->create([
                'user_id' => $user->id,
            ]);
            $post->update(['reports_number' => $reports_on_this + 1]);
            $reports_on_this = Post::where('id', $post->id)->value('reports_number');

            if ($reports_on_this  == $halfrange) {
                //notifiction
            }
            if ($reports_on_this  == $range) {

                $comment_id = Comment::where('post_id', $id)->get();

                // foreach ($comment_id as $value) {
                //     $value->reactions()->delete();
                //     $value->reports()->delete();
                // }


                $post->delete();
                //    $comments= $post->comments()->delete();

                //  $reactions=   $post->reactions()->delete();
                //  $reports=   $post->reports()->delete();



                //notification
                return 'report and Too Many Reports .. Post_Deleted ';
            }
            return 'report';
        }
    }
    public function report_or_cancelreport_on_comment(Request $request, $id)
    {

        $user = Auth::user();
        $user = User::find($user->id);

        $comment = Comment::find($id);
        $post_id = $comment->post_id;
        $post_location_type = Post::where('id', $post_id)->value('location_type');
        $post_location_id = Post::where('id', $post_id)->value('location_id');
        $range = 0;

        $comment_report = Report::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->value('id');
        $reports_on_this = Comment::where('id', $id)->value('reports_number');

        if ($post_location_type == 'App\Models\Community') {
            $subscribers =  Community::where('id', $post_location_id)->value('subscriber_counts');
            $range = round($subscribers / 10);
        } else if ($post_location_type == 'App\Models\Page') {

            $followes_counts =  Page::where('id', $post_location_id)->value('follower_counts');
            $range = round($followes_counts / 20);
        } else if ($post_location_type == 'App\Models\User') {
            $friend = new FriendController;
            $friends_ids = $friend->showFriends($request)->count();


            $range = round($friends_ids  / 30);
        }


        $halfrange = round($range) / 2;






        if ($comment_report != null) {
            $comment->update(['reports_number' => $reports_on_this - 1]);

            Report::where('location_type', 'App\Models\Comment')->where('location_id', $id)->where('user_id', $user->id)->delete();
            return 'cancel_report';
        } else if ($comment_report == null) {
            $comment->reports()->create([
                'user_id' => $user->id,
            ]);
            $comment->update(['reports_number' => $reports_on_this + 1]);
            $reports_on_this = Comment::where('id', $id)->value('reports_number');

            if ($reports_on_this  == $halfrange) {
                //notifiction
            }
            if ($reports_on_this  == $range) {




                $comment->delete();
                // $comment->reactions()->delete();
                // $comment->reports()->delete();



                //notification
                return 'report and Too Many Reports .. comment_Deleted ';
            }
            return 'report';
        }
    }
    public function share_post_1(Request $request)
    {

        $user = Auth::user();
        $user = User::find($user->id);
        $my_com =  $this->getMyCommuites();
        $my_own_page = $request->user()->pages()->find($user->id);
        //  return [[$my_com],$my_own_page];
        //  if ($my_own_page != null)
        return response()->json([
            'Message' => 'success',
            'Communites' => $my_com,
            'Pages' => [$my_own_page],
            'my_profile' => $user
        ]);
    }
    public function share_post_2(Request $request, $post_id, $location_id)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $place_type = $request->place_type;
        $location_type = '';

        if ($place_type == 'community') {
            $community = Community::find($location_id);
            $post = $community->posts()->create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'user_id' => $user->id
            ]);
        } else if ($place_type == 'page') {


            $page = Page::find($location_id);

            $post = $page->posts()->create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'user_id' => $user->id
            ]);
        } else if ($place_type == 'profile') {
            $post = $user->locationPosts()->create([
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'user_id' => $user->id
            ]);
        }

        $share = SharePost::create([
            'shared_post' => $post_id,
            'current_post' => $post->id
        ]);
        return    $share;
    }
    public function avtive_stories(Request $request)
    {

        $user = Auth::user();
        $user = User::find($user->id);


        $friend = new FriendController;
        $friends_ids = $friend->showFriends($request)->pluck('id');

        $users = Post::whereIn('user_id', $friends_ids)->where('type', 'Story')->pluck('user_id');

        $storyies_time = Post::whereIn('user_id', $friends_ids)->where('type', 'Story')->pluck('created_at');

        $arr = [];
        foreach ($storyies_time as $key) {
            $old_datetime = Carbon::parse($key)->format('Y-m-d H:i');
            if (now()->diffInHours($old_datetime) <= 24) {
                $arr[] = $key;
            }
        }

        $users = Post::whereIn('user_id', $friends_ids)->where('type', 'Story')->whereIn('created_at', $arr)->pluck('user_id')->toArray();
        $all = array_unique($users);

        $active = [];


        foreach ($all as  $value) {
            $id = $value;
            $active_sroties = collect(Media::where('model_id', $value)->where('collection_name', 'avatars')->get())->map(function ($media) {
                return   $medi = $media->getUrl();
            });
            $active[] = [$id, $active_sroties];
        }
        return response()->json([
            'data' => $active,
        ]);
    }
    public function showstory(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);

        $storyies_time = Post::where('user_id', $id)->where('type', 'Story')->pluck('created_at');

        foreach ($storyies_time as $key) {
            $old_datetime = Carbon::parse($key)->format('Y-m-d H:i');
            if (now()->diffInHours($old_datetime) <= 24) {
                $arr[] = $key;
            }
        }
        // $arr2 = [];
        // foreach ($arr as $final) {
        //     $new_datetime = $final->toISOString();
        //     $arr2[] = $new_datetime;
        // }
        //  return $arr2;
        $storyies = Post::where('user_id', $id)->where('type', 'Story')->whereIn('created_at', $arr)->pluck('id')->toArray();
        return $this->ExtraInfo_Post($storyies, $user, 'story');
        return $arr;
    }
    public function agree_or_cancelagree_challenge(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        $post_type = Post::where('id', $id)->first()->type;
        $post = Post::where('id', $id)->first();
        $count = Post::where('id', $id)->value('Approvals_counter');
        $location_type = $post->location_type;
        $location_id = $post->location_id;

        $range = 0;
        $user_degree = $user->expert()->value('years_as_expert');

        if ($user_degree == null) {
            return ' not expert .. go out donkey';
        } else if ($post_type != 'Challenge') {
            return ' post isnt challenge .. go out donkey';
        } else {
            if ($location_type == 'App\Models\Community') {
                $subscribers =  CommunityUser::where('community_id', $location_id)->pluck('user_id');
                $numexperts = Expert::whereIn('user_id', $subscribers)->count();
                $range = round($numexperts / 10);
            }



            $user_agree = Agree::where('post_id', $id)->where('user_id', $user->id)->value('id');
            if ($user_agree == null) {
                $post->agrees()->create([

                    'user_id' => $user->id,
                ]);

                $post->update([

                    'Approvals_counter' => $count + 1,
                ]);
                $count = Post::where('id', $id)->value('Approvals_counter');

                if ($count  == $range) {
                    $post->update([
                        'type' => 'Accepted Challenge'
                    ]);
                    return 'Voted .. and post ACCEPTED';
                }
                return 'voted';
            } else {
                $user_agree = Agree::where('post_id', $id)->where('user_id', $user->id)->delete();

                $post->update([

                    'Approvals_counter' => $count - 1
                ]);
                return 'cancel voted';
            }
        }
    }
    public function editpost(Request $request, $id)
    {
        $user_id =  $request->user()->id;

        $post =  Post::where('id', $id)->where('user_id', $user_id)->value('id');
        if ($post == null) {
            return response()->json([
                'message' => 'you cant'
            ]);
        } else {
            $post =  Post::where('id', $id)->where('user_id', $user_id)->update($request->all());
        }
        $updated = Post::where('id', $id)->where('user_id', $user_id)->get();
        return response()->json([
            'message' => 'updated',
            'data' => $updated
        ]);
    }

    public function deletepost(Request $request, $id)
    {
        $user_id =  $request->user()->id;

        $post =  Post::where('id', $id)->where('user_id', $user_id)->value('id');
        if ($post == null) {
            return response()->json([
                'message' => 'you cant'
            ]);
        } else {
            $post =  Post::where('id', $id)->where('user_id', $user_id);


            // $post->comments()->delete();
            // $post->reactions()->delete();
            // $post->reports()->delete();
            $post->delete();
        }
        return response()->json([
            'message' => 'deleted',
        ]);
    }

    public function getcommunityInfo(Request $request, $community_id)
    {

        $user = Auth::user();
        $user_id =  $request->user()->id;

        $type = $request->type;
        $locationt = 'community.' . $type;


        $l = counterpost::where('location', $locationt)->where('user_id', $user->id)->value('counter_post');

        if ($l == 0) {
            counterpost::create([
                'counter_post' => $l + 1,
                'user_id' => $user->id,
                'location' => $locationt
            ]);
        } else {
            $count = counterpost::where('location', $locationt)->where('user_id', $user->id)->update([
                'counter_post' => $l + 1
            ]);
        }

        $v =   counterpost::where('location', $locationt)->where('user_id', $user->id)->value('counter_post');


        $community =   Community::where('id', $community_id)->first();
        $me = collect(Media::where('collection_name', 'communities_photos')->where('model_id', $community_id)->get())->map(function ($media) {
            $fullPath = str_replace('\\', '/', $media->getUrl());
            $publicPath = Str::after($fullPath, 'http://127.0.0.1:8000/');
            return $publicPath;
        })->all();

        if ($type == 'all') {
            $posts_ids = Post::where('location_type', 'App\Models\Community')->where('location_id', $community_id)->pluck('id');
        } else {

            $posts_ids = Post::where('location_type', 'App\Models\Community')->where('location_id', $community_id)->where('type', $type)->pluck('id');
        }

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
            $count = counterpost::where('location', $locationt)->where('user_id', $user->id)->delete();

            return 'end posts >> referssh';
        }
        return response()->json([
            'Message' => 'success',
            'photo' => $me,
            'info' => $community,
            'data' => ['posts' => $first_fifteen],

        ]);
    }
}

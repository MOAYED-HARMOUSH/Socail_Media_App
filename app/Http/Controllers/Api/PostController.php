<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create_from_profile(Request $request){ // on home page

        $user = Auth::user();
        $user = User::find($user->id);

        $post_type= $request->type;
        $image=$request->image;
        $vedio=$request->vedio;

        if($post_type !=  'Job Opportunities')
{
        $create= $user->locationPosts()->create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'user_id' => $user->id
        ]);
        $POST_ID=$create->id;
        if($image !=null && $request->hasFile('image'))
{

    $user->addMediaFromRequest('image')->toMediaCollection('post_photos');
           $post_photos = $user->getFirstMedia('post_photos');


         $create->photos()->create([
'media_id'=>$create->id
         ]);
}
if($vedio !=null && $request->hasFile('vedio'))
{
    $user->addMediaFromRequest('vedio')->toMediaCollection('post_vedios');
    $post_vedios = $user->getFirstMedia('post_vedios');

    $create->videos()->create([
        'media_id'=>$create->id
                 ]);
}
        $message='created ' ;
    return response()->json($create,200,[$message]);
    }
    else;
    }
    public function getmedia() // just test
    {
        $user=Auth::user();
        return  $user->getFirstMedia('post_vedios'); // work no propleme
    }
}


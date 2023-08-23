<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FavoritePost;
use App\Models\Post;

class FavoritePostController extends Controller
{
    public function create(Request $request)
    {
      $post=  Post::find($request->id);
      $id=$request->id;
     $his_saved= $request->user()->favoritePosts->pluck('id');
        foreach ($his_saved as $key) {
if($key==$id)
{
 $result='yes';
 if($result=='yes')
 {

     $request->user()->favoritePosts()->detach($request->id);
     return response()->json([
         'message'=>'cancel save success'
     ]);
     }

}else
{$result='no';

}}

$request->user()->favoritePosts()->attach($request->id);

    return   response()->json([
        'Message' => 'save success'
    ]);


    }

    public function show(Request $request, PostController $postController)
    {
       $saved_posts = $request->user()->favoritePosts->pluck('id');
        // $saved_posts->pluck('id');
      $saved=  $postController->ExtraInfo_Post($saved_posts, $request->user());
if($saved == null)
        return response()->json([
            'Message' => 'end posts >> referssh',
            'data' => ['posts' => []]
        ]);
        else
        return   response()->json([
            'Message' => 'success',
            'data' => ['posts' => $saved]
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

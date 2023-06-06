<?php

namespace App\Http\Controllers\Api;

use App\Models\FavoritePost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoritePostController extends Controller
{
    public function create(Request $request, $id)
    {
        $user = Auth::user();
        return FavoritePost::create([
            'user_id' => $user->id,
            'post_id' => $id
        ]);
    }

    public function show(Request $request)
    {
        $user=Auth::user();
        return  FavoritePost::where('user_id',$user->id)->get();
    }

    public function destroy(Request $request,$id)
    {
        $user=Auth::user();
        return  FavoritePost::where('user_id',$user->id)->where('post_id',$id)->delete();
    }
}

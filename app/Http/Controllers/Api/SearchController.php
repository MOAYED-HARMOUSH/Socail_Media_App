<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $type = strtolower($request->type);
        switch ($type) {
            case 'user':
                return User::where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->get();
            case 'community':
                return Community::where('name', 'like', '%' . $request->search . '%')
                    ->get();
            case 'page':
                return Page::where('name', 'like', '%' . $request->search . '%')
                    ->get();
            case 'post':
                return Post::where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->get();
            case 'comment':
                return Comment::Where('content', 'like', '%' . $request->search . '%')
                    ->get();
            default:
                # code...
                break;
        }
    }
}

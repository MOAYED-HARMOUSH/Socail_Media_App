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
        $this->addIfNotExist($request);

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

    private function addIfNotExist(Request $request)
    {
        $history = $request->user()->searchHistory()->firstOrCreate([
            'words' => json_encode([$request->search])
        ]);

        $words = json_decode($history->words);
        if (!in_array($request->search, $words)) {
            $words[] = $request->search;
            $history->update(['words' => json_encode($words)]);
        }
    }

    public function index(Request $request)
    {
        return $request->user()->searchHistory()->get();
    }
}

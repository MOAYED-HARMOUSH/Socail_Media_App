<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Community;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SearchController extends Controller
{
    public function search(Request $request, PostController $postController)
    {
       // $this->addIfNotExist($request);
$array=[];
        $type = strtolower($request->type);
        switch ($type) {
            case 'user':
                 $users= User::where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->get();
                    if($users == null )
                    {    return response()->json([
                        'message'=>'no users ',
                        'data'=>[]
                    ]);
                    }
foreach ($users as $key ) {
    $poster_photo = collect(Media::where('model_id', $key->id)->where('collection_name', 'avatars')->get())->map(function ($media) {
        return $media->getUrl();
    })->first();

$array=[
    'name'=>$key->first_name . ' '. $key->last_name,
    'image'=>$poster_photo,
];

}
return response()->json([
    'message'=>'succes',
    'data'=>$array
]);

            case 'community':
                return Community::where('name', 'like', '%' . $request->search . '%')
                    ->get();
            case 'page':
                return Page::where('name', 'like', '%' . $request->search . '%')
                    ->get();
            case 'post':
                $post = Post::where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->get()->pluck('id');
                return $postController->ExtraInfo_Post($post, $request->user());
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
        $history = $request->user()->searchHistory()->first();
        if ($history == null)
            $request->user()->searchHistory()->create(['words' => json_encode([$request->search])]);

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

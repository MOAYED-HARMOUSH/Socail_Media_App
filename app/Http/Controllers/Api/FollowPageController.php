<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\User;
use App\Models\PageUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowPageController extends Controller
{
    public function create(Request $request, $id)
    {
        $user = Auth::user();

        $testifadmin = Page::where('admin_id', $user->id)->pluck('id');
        foreach ($testifadmin as $key => $value) {
            if ($id == $value)
                return 'admin of the page cant follow ';
        }

        $testid = Page::where('id', $id)->value('id');
        if (!$testid) {
            return 'undefind';
        } else {
            $test = PageUser::where('user_id', $user->id)->where('page_id', $id)->value('id');
            if (!$test) {
                PageUser::create([
                    'user_id' => $user->id,
                    'page_id' => $id
                ]);
                return 'followed';
            } else return 'already followed';
        }
    }

    public function delete(Request $request,$id)
    {
        $user = Auth::user();

        $testifadmin2 = Page::where('admin_id', $user->id)->pluck('id');
        foreach ($testifadmin2 as $key => $value) {
            if ($id == $value)
                return 'admin of the page cant not follow ';
        }
        $testid = Page::where('id', $id)->value('id');
        if (!$testid) {
            return 'undefind';
        } else {
            $test = PageUser::where('user_id', $user->id)->where('page_id', $id)->value('id');
            if ($test) {
                $ww = PageUser::where('user_id', $user->id)->where('page_id', $id)->delete();
                return 'not followed';
            } else return 'already not followed';
        }
    }

    public function show()
    {
        $user = Auth::user();
        $user2 = User::find($user->id);

        return $user2->Follow_Page()->get();
        //    $a=$user2->Follow_Page()->pluck('id');
        //    return $pages = Page::whereIn('id', $a)->get();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        return $user->Page()->get();
    }

    public function create(Request $request, $id)
    {
        $user = Auth::user();
        $fields = $request->validate([
            'email' => 'bail|required|string',
            'bio' => 'bail|required|string',
            'cover_image' => 'bail|required|string',
            'image_name' => 'bail|required|string',
            'type' => 'bail|required|string',
            'name' => 'bail|required|string',

        ]);
        return $page = Page::create([
            'email' => $fields['email'],
            'bio' => $fields['bio'],
            'cover_image' => $fields['cover_image'],
            'image_name' => $fields['image_name'],
            'type' => $fields['type'],
            'name' => $fields['name'],
            'admin_id' => $user->id
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user=Auth::user();
        $user=User::find($user->id);
        $del = Page::where('admin_id', $user->id)->where('id', $id)->first();
        if (!$del) {
            return 'donkey';
        } else {
            $del->delete();
            return 'Page deleted successfully';
        }
    }

    public function edit(Request $request, $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAvatar(Request $request) //for Test Only
    {
        return $request->user()->getFirstMedia('avatars');
    }

    public function completeInfo(Request $request)
    {
        if ($request->has('study_semester'))
            $request->user()->student()->create($request->all());

        if ($request->has('companies'))
            $request->user()->expert()->create($request->all());

        return $request->user()->update($request->all());
    }
}

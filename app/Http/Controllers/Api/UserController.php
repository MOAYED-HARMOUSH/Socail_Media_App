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
            $request->user()->expert()->create([
                'companies' => json_encode(explode(',', $request->companies)),
                'years_as_expert' => $request->years_as_expert,
                'work_at_company' => $request->work_at_company
            ]);

        return $request->user()->update($request->all());
    }
}

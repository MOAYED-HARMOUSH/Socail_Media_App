<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Expert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ExpertController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->expert_id == null) {
            $expert = Expert::create([
                'section' => $request->section,
                'start_year' => $request->start_year,

                'work_at_company' => $request->work_at_company,
                'years_as_expert' => $request->years_as_expert,
                'companies' => $request->companies,


            ]);
            $user = User::where('id', $user->id)->update([
                'expert_id' => $expert->id
            ]);
        } else
            return ' already expert';
    }
}

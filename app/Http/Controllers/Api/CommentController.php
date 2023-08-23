<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Summary of index
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return mixed
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        return Comment::create([
            'content' => $request->content,
            'post_id' => $request->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comments)
    {
        $user = Auth::user();
        $user = User::find($user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $user = User::find($user->id);
        return $user->Comments()->where('id', $request->id)->delete();
    }
}

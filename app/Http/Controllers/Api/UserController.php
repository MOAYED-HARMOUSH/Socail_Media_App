<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\counterpost;

class UserController extends Controller
{
    /**
     * Summary of completeInfo
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
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

        $request->user()->update($request->all());

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function showAnotherProfile(Request $request)
    {
        $user = User::find($request->id);
        $receiver = $request->user()->senders()->where('friends.receiver', $request->id)->first();
        $sender = $request->user()->receivers()->where('friends.sender', $request->id)->first();
        // return var_dump($receiver->is_approved===[]);
        if ($receiver != null) {
            if (!is_null($receiver->is_approved))
                $button = ($receiver->is_approved == false) ? 'Rejected' : 'Accepted';
            else
                $button = 'Cancel Request?!';
        } elseif ($sender != null) {
            $button = ($sender->is_approved == true) ? 'Reject' : 'Accept';
        } else {
            $button = 'Send Friend Request';
        }
        if ($user != null)
            $user->getFirstMedia('avatars');
        $user = collect($user)->except(['email', 'email_verified_at', 'created_at', 'updated_at']);
        return response()->json([
            'Message' => 'success',
            'data' => $user,
            'button' => $button
        ]);
    }

    public function showMyProfile(Request $request)
    {
        $user = $request->user();
        $url = $user->getFirstMedia('avatars')->original_url;
        $user->student;
        $user->expert;
        $user->specialty;
        return response()->json([
            'Message' => 'success',
            'user' => $user,
            'media_url' => $url
        ]);
    }

    /**
     * Summary of edit
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('avatars');
        }

        $this->editSpecialty($request);

        if ($request->has('study_semester')) {
            $student = $user->student()->first();
            $student->update($request->all());
        }

        if ($request->has('companies'))
            $user->expert()->update([
                'companies' => json_encode(explode(',', $request->companies)),
                'years_as_expert' => $request->years_as_expert,
                'work_at_company' => $request->work_at_company
            ]);

        $user->update($request->all());
        //ToDo: Verification Email Required when Changing Email

        return response()->json([
            'Message' => 'success'
        ]);
    }

    /**
     * Summary of editSpecialty
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function editSpecialty(Request $request)
    {
        $old_specialty = $request->user()->specialty()->first();
        $old_specialty_arr = array_merge(
            [$old_specialty->specialty],
            explode(',', $old_specialty->section),
            explode(',', $old_specialty->framework),
            explode(',', $old_specialty->language)
        );

        $old_specialty->update($request->all());

        $new_specialty_arr = array_merge(
            [$request->specialty],
            explode(',', $request->section),
            explode(',', $request->framework),
            explode(',', $request->language)
        );

        $new_specialties_arr = array_diff($new_specialty_arr, $old_specialty_arr);
        $old_specialties_arr = array_diff($old_specialty_arr, $new_specialty_arr);

        $new_specialty = implode(',', $new_specialties_arr);
        $old_specialty = implode(',', $old_specialties_arr);

        CommunityController::addUserToCommunity($new_specialty, $request->user());
        CommunityController::removeUserFromCommunity($old_specialty, $request->user());

        return response()->json([
            'Message' => 'success'
        ]);
    }

    public function createRandomUsers($count)
    {

        $factory = User::factory();

        for ($i = 0; $i < $count; $i++) {
            $user = $factory->create();
            $token = $user->createToken('Sign up', [''], now()->addYear())->plainTextToken;
            $arr[$i] = $token;
            $users[$i] = $user;
        }
        app()->make(\Database\Seeders\MainSeeder::class)->run();

        return $arr;
    }

}

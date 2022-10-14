<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDetailRequest;
use App\Models\User;
use App\Models\UserDetail;

class UserDetailController extends Controller
{
    public function show(User $user)
    {
        return response()->json($user->userDetail, 200);
    }

    public function update(UserDetailRequest $request, User $user)
    {
        $userDetail_data = $request->validated();
        $userDetail = $user->userDetail;
        if (!$userDetail) {
            $userDetail_data['user_id'] = $user->id;
        }
        $userDetail = UserDetail::updateOrCreate(['user_id' => $user->id], $userDetail_data);
        return response()->json($userDetail, 200);
    }

    public function updateUser(User $user)
    {
        $user_data = request()->validate([
            'fname' => 'required',
            'lname' => 'required'
        ]);
        $user->update($user_data);
        return response()->json($user, 200);
    }

    public function uploadImage(User $user)
    {
        $image = request()->validate([
            'image' => 'required|image'
        ]);
        if ($image) {
            $image_path = request()->file('image')->store('public/user/profiles');
            $user->update([
                'image' => basename($image_path)
            ]);

            return response()->json($user, 200);
        } else {
            return response()->json(['result' => 'failed'], 201);
        }
    }

    public function uploadId(UserDetail $userDetail)
    {
        $id = request()->validate([
            'id_document' => 'required|max:10240'
        ]);
        if ($id) {
            $id_document_path = request()->file('id_document')->store('public/user/id');
            $userDetail->update([
                'id_document' => basename($id_document_path)
            ]);

            return response()->json($userDetail, 200);
        } else {
            return response()->json(['result' => 'failed'], 201);
        }
    }
}

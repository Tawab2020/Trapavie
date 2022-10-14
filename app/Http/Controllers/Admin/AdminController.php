<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function updateProfile(AdminRequest $request, Admin $admin)
    {
        $admin_data = $request->validated();
        $admin->update($admin_data);
        return response()->json($admin, 200);
    }

    public function uploadImage(Admin $admin)
    {
        $image = request()->validate([
            'image' => 'required|image'
        ]);
        if ($image) {
            $image_path = request()->file('image')->store('public/admin/images/' . $admin->id);
            $admin->update([
                'image' => basename($image_path)
            ]);

            return response()->json($admin, 200);
        } else {
            return response()->json(['result' => 'failed'], 201);
        }
    }

    public function changePassword(ChangePasswordRequest $request, Admin $admin)
    {
        $pass_data = $request->validated();
        if (!Hash::check($pass_data['currentPassword'], $admin->password)) {
            return response()->json([
                'message' => 'password change failed',
                'errors' => [
                    'currentPassword' => ['Your password was incorrect.'],
                ]
            ], 400);
        } else {
            $admin->update(['password' => Hash::make($pass_data['password'])]);
            return response()->json("password changed successfully", 200);
        }
        return response()->json("password change failed", 402);
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\{Validator};

class HomeController extends Controller
{
    public function getProfile()
    {
        $user    =   $this->loggedInUser;

        // $user       =   User::find($user);

        return response()->json(['status' => true, 'message' => 'Get profile successfully', 'data' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user    =   $this->loggedInUser;

        $validator = Validator::make($request->all(), [
            'name'  => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'city'  => 'nullable|string|max:50',
            'region' => 'nullable|max:50',
            'street' => 'nullable|max:50',
            'building_no' => 'nullable|max:11',
            'postal_code' => 'nullable|max:11',
            'profile' => 'nullable|mimes:jpg,png,jpeg|max:2024'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        // upload profile image by the helper function
        if ($request->hasFile('profile')) {
            $request['image']  =   CommonHelper::fileUpload($request->file('profile'), 'user');
        }

        $user->update($request->all());

        return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'data' => $user]);
    }
}

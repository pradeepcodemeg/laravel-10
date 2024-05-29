<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, UserOtp};
use Illuminate\Support\Facades\{Auth, Hash, Validator};

class AuthController extends Controller
{
    // Send the OTP to the guest user and insert data into user otp table  
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:50',
                'phone'             => 'required|max:12|unique:users',
                'email'             => 'nullable|string|email|max:50|unique:users',
                'city'              => 'nullable|string|max:50',
                'password'          => 'required|min:8|confirmed',
                'password_confirmation'  => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            $otp    =   random_int(0000, 9999);

            UserOtp::updateOrCreate(['phone' =>  $request->phone], [
                'otp'   =>  $otp
            ]);

            return response()->json(['status' =>  true, 'message' => 'OTP has been sent successfully', 'data' => $otp], 200);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }

    function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $otp    =   random_int(0000, 9999);

        UserOtp::updateOrCreate(['phone' =>  $request->phone], [
            'otp'   =>  $otp
        ]);

        return response()->json(['status' =>  true, 'message' => 'OTP resent successfully', 'data' => $otp], 200);
    }

    // verify the OTP and created the user with provide data, (remove his data from otp table)
    public function verifyOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'              => 'required|string|max:50',
                'phone'             => 'required|max:12|unique:users',
                'email'             => 'nullable|string|email|max:50|unique:users',
                'city'              => 'nullable|string|max:50',
                'password'          => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
                'otp'                   => 'required|integer|digits:4'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            $checkOTP   =   UserOtp::where(['phone' => $request->phone, 'otp' => $request->otp])->first();

            if (!$checkOTP) {
                return response()->json(['status' =>  false, 'message' => 'OTP is invalid'], 403);
            }

            $request['password']    =   Hash::make($request->password);

            // Create a new user
            $user = User::create($request->all());

            // delete the otp data from OTP table
            if ($user) {
                $checkOTP->delete();
            }

            // create authentication token
            $token = $user->createToken('user_token')->plainTextToken;

            // authenticate Registered User
            Auth::login($user);

            return response()->json(['status' =>  true, 'token' => $token, 'message' => 'User successfully registered', 'data' => $user], 201);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }

    // user can login with phone and password
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone'         => 'required',
                'password'      => 'required',
                'device_token'  => 'nullable'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            // trying to attempt login with credentials 
            if (!Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
                return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // create the token for the user
            $token = $user->createToken('user_token')->plainTextToken;

            // update the device token
            $user->update(['device_token' => $request->device_token]);

            return response()->json(['status' => true, 'message' => 'Login successfully', 'token' => $token, 'data' => $user], 200);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }

    public function forgot(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone'         => 'required|exists:users,phone'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            $otp    =   random_int(0000, 9999);

            UserOtp::updateOrCreate(['phone' =>  $request->phone], [
                'otp'   =>  $otp
            ]);

            return response()->json(['status' => true, 'message' => 'Forgot successfully send OTP on your registered phone', 'otp' => $otp], 200);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }

    // verify the OTP after forgot password user with provide data, (remove his data from otp table)
    public function verifyOTPAfterForgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:12|exists:users,phone',
            'otp'   => 'required|integer|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $checkOTP   =   UserOtp::where(['phone' => $request->phone, 'otp' => $request->otp])->first();

        if (!$checkOTP) {
            return response()->json(['status' =>  false, 'message' => 'OTP is invalid'], 403);
        }

        return response()->json(['status' =>  true, 'message' => 'OTP verified successfully'], 201);
    }

    // reset password after the forgotten
    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone'         => 'required|exists:users,phone',
                'password'          => 'required|min:8|confirmed',
                'password_confirmation'  => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            $user   =   User::where('phone', $request->phone)->first();

            $encryptedPassword   =   Hash::make($request->password);

            $user->update(['password' => $encryptedPassword]);

            // delete the otp data from OTP table
            if ($user) {
                UserOtp::where('phone', $request->phone)->delete();
            }

            // create authentication token for the user
            $token = $user->createToken('user_token')->plainTextToken;

            // authenticate User
            Auth::login($user);

            return response()->json(['status' => true, 'message' => 'The password has been reset successfully.', 'token' => $token, 'data' => $user], 200);
        } catch (\Exception $e) {

            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
                'password_confirmation' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
            }

            $user = Auth::user(); // Get the authenticated user

            // Check if the provided current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['status' => false, 'message' => 'Current password is incorrect.'], 401);
            }

            // Hash and update the new password
            $encryptedPassword = Hash::make($request->password);
            $user->update(['password' => $encryptedPassword]);

            return response()->json(['status' => true, 'message' => 'The password has been changed successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => __('message.fatal_error'), 'error' => $e->getMessage()], 500);
        }
    }
}

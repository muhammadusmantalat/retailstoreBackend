<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        // return $request;

    }


    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->where('user_type', 'store_Manager')->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid Email and Password']);
        }

        if ($user->is_active == 0) {
            return response()->json(['message' => 'Your Account has been deactivated by Admin']);
        }
        $userId = $user->id;
        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }
        // return $userId;
        // Generate a new token for the user
        $token = $user->createToken($request->email)->plainTextToken;
        // return $token;

        // Return the successful login response
        return response()->json([
            'status' => 'ok',
            'message' => 'Store Manager Login Successfully',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /*send otp through mail in forgot password */
    // public function forgotPassword(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError($validator->errors()->first());
    //     }
    //     $user = User::where('email', $request->email)->where('user_type', 'store_Manager')->first();
    //     // return  $user;
    //     if ($user) {

    //         // if second request for otp then exist otp delete
    //         DB::table('password_resets')->where('email', $request->email)->delete();
    //         $otp = random_int(1000, 9999);
    //         DB::table('password_resets')->insert([
    //             'email' => $request->email,
    //             'token' => $otp,
    //         ]);
    //         // return $user;
    //         Mail::to($request->email)->send(new ForgotPassword($otp));
    //         return response()->json([
    //             'status' => 'Ok',
    //             'message' => 'OTP has been send successfully on your email'
    //         ], 200);
    //     }

    //     return response()->json([
    //         'status' => 'failed',
    //         'message' => 'Invalid email address'
    //     ], 400);
    // }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = User::where('email', $request->email)
            ->where('user_type', 'store_Manager')
            ->first();

        // return $user;



        if ($user) {
            // Delete any existing OTP
            DB::table('password_resets')->where('email', $request->email)->delete();

            // Generate a new OTP
            $otp = random_int(1000, 9999);

            // Insert the new OTP into the password_resets table
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $otp,
                'created_at' => now(), // Add a timestamp for record
            ]);





            Mail::to($request->email)->send(new ForgotPassword($otp));

            return response()->json([
                'status' => 'Ok',
                'message' => 'OTP has been sent successfully to your email'
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Invalid email address'
        ]);
    }



    // public function logout()
    // {

    //     return 'logout';
    // }




    public function notification()
    {
        $SERVER_API_KEY = 'AAAAGAYvVyg:APA91bHn703e-8w6gHludk4Wd8Uj1HjFXYp6933n-ZQx-a8qM_Hu86nJh-XlVv7CBUXikcOICEN1TW4sswuAjjeD7RWaCwttgE3R26ZvLGdwkIgHR9HigoxyZusqQucp-i5vdjyqWww8';
        $data = [
            'to' => 'fyRn1eGwRiKUYISE1ePZoU:APA91bEmN9xAvoZpjfcumQ7hvlcG-gFVWaE9vUh8XpobiA5dFKxGHhCxVP8jwHm-VD_gpb1EATIGth3f-WsXvhMmQry6hkCYwRROMZmUO21ghOxcoGm8xulSKkLKLZw3YA-bH_qPnzic',
            'notification' => [
                'title' => "Request",
                'body' => 'asdnsajkldnsalkdmnsakdmsamsadmsadms',
            ],
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        dd($response);
    }

    /*confirm otp code */
    public function confirmToken(Request $request)
    {
        $passwordReset = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();
        if ($passwordReset) {
            // OTP is valid
            return response()->json([
                'status' => 'Ok',
                'message' => 'OTP has been successfuly verified'
            ]);
        }
        return response()->json([
            'status' => 'Failed',
            'message' => 'Invalid OTP'
        ]);
    }


    /*change user password */
    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()]);
        }
        if ($request->password != $request->confirm_password) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Password does`t Match',
            ], 400);
        }
        $password = bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        if (User::where('email', $request->email)->update($tags_data)) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Password Updated Successfully',
            ]);
        }
    }

    public function getEmail()
    {
        // Assuming there's only one admin or you know how to identify the admin
        $admin = Admin::first(); // Adjust the query as needed

        if ($admin) {
            return response()->json(['email' => $admin->email], 200);
        }

        return response()->json(['error' => 'Admin not found'], 404);
    }


    public function logout(Request $request)
    {
        try {
            // Ensure the user is authenticated before trying to update the FCM token and delete the token
            if ($request->user()) {
                $user = $request->user();
                // return $user;

                // Update the FCM token to null
                $user->fcm_token = null;
                $user->save();

                // Revoke the token that was used to authenticate the current request
                $user->currentAccessToken()->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Logout Successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No authenticated user found.',
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}

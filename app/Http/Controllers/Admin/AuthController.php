<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function getLoginPage()
    {
        return view('admin.auth.login');
    }
    // public function Login(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);
    //     // $remember_me = ($request->remember_me) ? true : false;
    //     // if (!auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
    //     //     return back()->with('err_message', 'Invalid email or password');
    //     // }
    //     // return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     $remember_me = ($request->remember_me) ? true : false;
    //     if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }
    //     else
    //     {
    //         return redirect('admin-login')->with('message','Invalid Email and password');
    //     }
    // }

    //####################################################################################################################################
    // public function Login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $credentials = $request->only('email', 'password');
    //     // return $credentials;
    //     $remember_me = $request->has('remember_me');


    //     // Attempt to authenticate using the 'admin' guard
    //     if (auth()->guard('admin')->attempt($credentials, $remember_me)) {
    //         // dd('sdfgh');
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }

    //     if (auth()->guard('web')->attempt($credentials, $remember_me)) {
    //         $user = Auth::guard('web')->user();

    //         if ($user->user_type == 'subadmin') {
    //             return redirect()->to('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //         } else {
    //             // Handle the case where the user is authenticated but not a subadmin
    //             return redirect()->back()->with(['status' => false, 'message' => 'You are not authorized to access this section.']);
    //         }
    //     } else {
    //         // Handle the case where the authentication attempt fails
    //         return redirect()->back()->with(['status' => false, 'message' => 'Invalid email or password']);
    //     }
    // }


    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember_me = $request->has('remember_me');

        // Attempt to authenticate using the 'admin' guard
        if (auth()->guard('admin')->attempt($credentials, $remember_me)) {
            return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
        }

        $users = User::where('email', $credentials['email'])->get();
        // return $users;

        foreach ($users as $user) {
            // Check if the user type is 'subadmin'
            if ($user->user_type == 'subadmin') {
                // Attempt to authenticate this subadmin user
                if (auth()->guard('web')->attempt(['email' => $user->email, 'password' => $credentials['password']], $remember_me)) {
                    return redirect()->to('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
                }
            } else {
                // Handle the case where the user is authenticated but not a subadmin
                return redirect()->back()->with(['status' => false, 'message' => 'You are not authorized to access this section.']);
            }
        }

        // If no subadmin user is authenticated, return with error message
        return redirect()->back()->with(['status' => false, 'message' => 'Invalid email or password']);
    }


    // public function Login(Request $request)
    // {
    //     // Validate the incoming request data
    //     $request->validate([
    //         'email' => 'required|email', // Ensure email is in the correct format
    //         'password' => 'required',
    //     ]);

    //     // Prepare the credentials for authentication
    //     $credentials = $request->only('email', 'password');

    //     // Attempt to authenticate using the 'admin' guard
    //     if (auth()->guard('admin')->attempt($credentials)) {
    //         $request->session()->regenerate(); // Regenerate session to prevent session fixation
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }

    //     // Retrieve the subadmin user by email
    //     $user = User::where('email', $credentials['email'])
    //                 ->where('user_type', 'subadmin')
    //                 ->first(); // Fetch the first subadmin user with this email

    //     // // Check if user exists and verify the password
    //     if ($user && Hash::check($credentials['password'], $user->password)) {

    //         $request->session()->regenerate(); // Regenerate session to prevent session fixation
    //         return redirect('admin/dashboard')->with(['status' => true, 'message' => 'Login Successfully']);
    //     }

    //     // Redirect back with an error message if authentication fails
    //     return redirect()->back()->with(['status' => false, 'message' => 'Invalid email or password']);
    // }

}

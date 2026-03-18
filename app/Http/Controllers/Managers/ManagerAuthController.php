<?php

namespace App\Http\Controllers\Managers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagerAuthController extends Controller
{
    public function getLoginPage()
    {
        return view('managers.auth.login');
    }


    public function Login(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // return $request;

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
        //     return redirect()->route('manager.main')->with(['status'=>true,'message'=>'Login Successfully']);
        // }
        // else
        // {
        //     return redirect('login')->with('message','Invalid Email and password');
        // }

        $credentials = $request->only('email', 'password');
        $store_Manager = User::where('email', $credentials['email'])->where('user_type', 'store_Manager')->first();
        // return $store_Manager;

        if (!$store_Manager) {
            return redirect()->route('login')->with('message', 'Store Manager not found or invalid credentials');
        }
        
        if ($store_Manager) {
            if ($store_Manager->is_active == 0) {

                return redirect()->route("login")->with(['status' => true, 'message' => 'Account has been deactivated by Admin']);
            }

            if (Auth::guard('web')->attempt($credentials)) {
                return redirect()->route('manager.main')->with(['status' => true, 'message' => 'Store Manager Login Successfully']);
            } else {
                return redirect('login')->with('message', 'Invalid Email and Password');
            }
        }
    }
}

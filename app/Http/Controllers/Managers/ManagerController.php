<?php

namespace App\Http\Controllers\Managers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;



class ManagerController extends Controller
{
    public function getManagerPage()
    {
        // $managerId =Auth::guard('web')->id();
        // $stores = Store::where('storeManger_id' ,$managerId)->get();
        return view('managers.index');
        // return view('managers.index' ,compact('stores'));
    }
    public function getProfilePage()
    {
        $data = User::find(Auth::guard('web')->id());
        // return $data;
        return view('managers.auth.profile', compact('data'));
    }
    public function updateProfile(Request $request)
    {
        // return $request;
        $request->validate([
            'first_name' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);
        $data = $request->only(['name', 'first_name', 'email','phone']);
        // return $data;
        User::find(Auth::guard('web')->id())->update($data);
        return redirect()->back()->with('message', 'Profile Updated Successfully');
    }

    public function forgetPassword()
    {
        return view('managers.auth.forgetPassword');
    }

    public function managerResetPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);

        $email = DB::table('password_resets')->where('email', $request->email)->first();
        if ($email) {
            return redirect()->back()->with('message', 'Reset Password Link Has Been Already Sent');

        } else {
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
            ]);
        }
        $data['url'] = url('manager_change_password', $token);
        Mail::to($request->email)->send(new ResetPasswordMail($data));
        return back()->with(['status' => true, 'message' => 'Reset Password Link Send Successfully']);
    }
    public function change_password($id)
    {
        $user = DB::table('password_resets')->where('token', $id)->first();
        if ($user) {
            return view('managers.auth.chnagePassword', compact('user'));
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required',
        ]);
        if ($request->password != $request->confirmed) {
            return redirect()->back()->with(['status' => true, 'message' => 'Password not matched']);
        }
        $password = bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        // return $request->email;
        if (User::where('email', $request->email)->update(['password' => bcrypt($request->password)])) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return redirect('login')->with(['status' => true, 'message' => 'Reset Password Successfully']);
        }
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('login')->with(['status' => true, 'message' => 'Logout Successfully']);
    }
}

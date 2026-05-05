<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //
    public function getdashboard(){
    $storesCount = Store::count();
    $storeManagersCount = User::where('user_type','store_Manager')->count();
    $productsCount = Product::count();
    // $productsCount = 0;
    // return [$storesCount,$storeManagersCount,$productsCount];
        return view('admin.index', compact('storesCount','storeManagersCount','productsCount'));
    }
    public function getProfile(){
        $data=Admin::find(Auth::guard('admin')->id());
        $subAdmin=User::find(Auth::guard('web')->id());
        // return $subAdmin;
        return view('admin.auth.profile',compact('data','subAdmin'));
    }

    public function update_profile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]);
        $data = $request->only(['name', 'email', 'phone']);

        return $request;
        // Check if an image was uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('/admin/assets/images/users/'), $filename);
            $data['image'] = 'public/admin/assets/images/users/' . $filename;
        }

        // Fetch the current authenticated admin (if applicable)
        $admin = Admin::find(Auth::guard('admin')->id());

        // Fetch the user (which could be a sub-admin)
        $user = User::find(Auth::guard('web')->id());

        // Update the admin profile if found
        if ($admin) {
            $admin->update($data);
        }

        // Check if the user exists and update their profile
        if ($user) {
            $user->update($data);
        } else {
            return back()->with(['status' => false, 'message' => 'User not found']);
        }

        return back()->with(['status' => true, 'message' => 'Profile Updated Successfully']);
    }




    public function forgetPassword(){
        return view('admin.auth.forgetPassword');
    }
    public function adminResetPasswordLink(Request $request){
        $request->validate([
            'email'=>'required|exists:admins,email',
        ]);
        $exists = DB::table('password_resets')->where('email',$request->email)->first();
        if ($exists){
            return back()->with(['status' => true, 'message' => 'Reset Password link has been already sent']);
        }else{
            $token = Str::random(30);
            DB::table('password_resets')->insert([
                'email'=>$request->email,
                'token'=>$token,
            ]);

            $data['url'] = url('change_password',$token);
            Mail::to($request->email)->send(new ResetPasswordMail($data));
            return back()->with(['status' => true, 'message' => 'Reset Password Link Send Successfully']);
        }
    }
    public function change_password($id)
    {

        $user = DB::table('password_resets')->where('token',$id)->first();

        if(isset($user))
        {
            return view('admin.auth.chnagePassword',compact('user'));
        }
    }

    public function resetPassword (Request $request)
    {

       $request->validate([
            'password' => 'required|min:8',
            'confirmed' => 'required',

        ]);
       if ($request->password !=$request->confirmed)
       {

           return back()->with(['status' => true, 'message' => 'Password not matched']);
       }
        $password=bcrypt($request->password);
        $tags_data = [
            'password' => bcrypt($request->password)
        ];
        if (Admin::where('email',$request->email)->update($tags_data)){
            DB::table('password_resets')->where('email',$request->email)->delete();
            return redirect('admin-login')->with(['status' => true, 'message' => 'Reset Password Successfully']);
        }


    }
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin-login')->with(['status' => true, 'message' => 'Logout Successfully']);
    }

    // public function logout(Request $request)
    // {
    //     if (Auth::guard('web')->check()) {
    //         Auth::guard('web')->logout();
    //     }
    //     if (Auth::guard('admin')->check()) {
    //         Auth::guard('admin')->logout();
    //     }
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect('admin-login')->with(['status' => true, 'message' => 'Log Out Successfully']);
    // }

}

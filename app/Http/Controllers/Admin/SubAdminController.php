<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SubAdminRegistration;
use App\Http\Controllers\Controller;
use App\Models\Permission_component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;


class SubAdminController extends Controller
{
    public function getSubadmin()
    {
        // $sub_admins = User::where('user_type','subadmin')->latest()->get();;
        // return view('admin.subadmin.index', compact('sub_admins'));
        $permissions = Permission::all();
        $sub_admins = User::orderBy('id','DESC')->where('user_type','subadmin')->get();
        foreach ($sub_admins as $sub) {
            $permissions_subadmin = Permission_component::where('user_id', $sub->id)->get();
            $sub->permissions = $permissions_subadmin;
        }
        // $sub_admins = Admin::orderBy('id','DESC')->where('role','subadmin')->get();
        return view('admin.subAdmin.index', compact('sub_admins','permissions'));
    }


    public function getAddSubadmin()
    {
        return view('admin.subAdmin.add');
    }

    public function create ()
    {

    }


    public function addSubadmin(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'phone_no' => 'required',
            ]
        );

        if ($request->user_type == 'subadmin') {
            $exists = User::where('email', $request->email)
                          ->where('user_type', 'subadmin')
                          ->exists();
            if ($exists) {
                return back()->with(['status' => false,'message' => 'Email Already Taken']);
            }
        }

        $password = '';
        for ($i = 0; $i < 6; $i++) {
            $password .= random_int(0, 9);
        }

        $data = new User();
        $data->first_name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone_no;
        $data->user_type = $request->user_type;
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('/uploads'), $filename);
            $data['image'] = 'public/uploads/' . $filename;
        }else {
            $data->image = 'public/admin/assets/images/avator.png';
        }
        $data->password = Hash::make($password);
        $data->save();
        $data['name'] = $data->name;
        $data['email'] = $data->email;
        $data['password'] = $password;
        // return $password;


        $message['email'] = $request->email;
        $message['password'] = $password;
        try {
            Mail::to($request->email)->send(new SubAdminRegistration($message));
            return redirect()->route('subadmin')->with(['status' => true, 'message' =>  'Subadmin Added Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
        // return redirect('admin/subadmin')->with(['status' => true, 'message' => 'Subadmin add Successfully']);
    }


    public function getEditSubadmin($id)
    {
        $data = User::find($id);
        return view('admin.subAdmin.edit', compact('data'));
    }



    public function updateSubadmin(Request $request)
    {
        // return $request;
        $request->validate(
            [
                'name' => 'required',
                // 'email' =>  'required|email|unique:users,email',
                'phone_no' => 'required',
                ]
            );
            // return $request;
            $data = User::find($request->id);
            $data->first_name = $request->name;
            // $data->email = $request->email;
            $data->phone = $request->phone_no;
            $data->user_type = 'subadmin';
            if ($request->hasfile('image')) {
                $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = time() . '.' . $extension;
            $file->move(public_path('/uploads'), $filename);
            $data['image'] = 'public/uploads/' . $filename;
        }else {
            $data->image = 'public/admin/assets/images/avator.png';
        }
        $data->save();

        return redirect('admin/subadmin')->with(['status' => true, 'message' => 'Subadmin Updated Successfully']);
    }



    public function subadminDelete($id)
    {
        // return $id;
        User::destroy($id);
        return redirect('admin/subadmin')->with(['status' => true, 'message' => 'Subadmin Deleted Successfully']);
    }


    public function fetchUserPermissions(User $user)
    {
        $permissions = $user->permissions()->get();
        return response()->json(['permissions' => $permissions]);
    }

    public function updatePermissions(Request $request, User $user)
    {
        try {

            $permissions = $request->input('permissions', []);
            // return   $permissions ;
            $permissions = array_map('intval', $permissions);
            $user->syncPermissions($permissions);
            return response()->json(['status' => true, 'message' => 'Subadmin Permissions Updated Successfully']);;
        } catch (\Exception $e) {
            return response()->json(['alert' => 'error', 'message' => 'An error occurred while updating permissions' . $e->getMessage()], 500);
        }
    }
}

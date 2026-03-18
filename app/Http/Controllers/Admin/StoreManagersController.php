<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Mail\UserActivated;
use Illuminate\Http\Request;
use App\Mail\UserDeactivated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\StoreManagerCrediential;
use App\Mail\AccountActivatedNotification;

class StoreManagersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storeManagers = User::where('user_type', 'store_Manager')->orderBy('id', 'desc')->get();

        return view('admin.storeManager.index', compact('storeManagers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.storeManager.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            // 'email' => 'required|email|unique:users,email|max:255',
            'email' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
        ]);

        if ($request->user_type == 'store_Manager') {
            $exists = User::where('email', $request->email)
                ->where('user_type', 'store_Manager')
                ->exists();
            if ($exists) {
                return back()->with(['status' => false, 'message' => 'Email already taken']);
            }
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/avator.png';
        }
        $is_active = '1';
        $password = random_int(10000000, 99999999);
        $storeManager = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone_no,
            'address' => $request->address,
            'image' => $image,
            'is_active' => $is_active,
            'user_type' => $request->user_type,
            'password' => Hash::make($password),
        ]);
        // return $storeManager;
        $message['email'] = $request->email;
        $message['password'] = $password;

        try {
            Mail::to($request->email)->send(new StoreManagerCrediential($message));
            return redirect()->route('store-manager.index')->with(['status' => true, 'message' => 'Store Manager Added Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the
     * specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $storeManager = User::find($id);
        return view('admin.storeManager.edit', compact('storeManager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([

            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone_no' => 'required',
            'address' => 'required'
        ]);

        $storeManager = User::find($id);

        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $storeManager->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
            $storeManager->image = $image;
        }

        $storeManager->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone_no,
            'address' => $request->address,
        ]);

        return redirect()->route('store-manager.index')->with(['status' => true, 'message' => 'Store Manager Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // public function destroy($id)
    // {
    //     // Check if the store manager has any associated stores
    //     $storeCount = Store::where('storeManger_id', $id)->count();

    //     if ($storeCount > 0) {
    //         // If the store manager has stores, prevent deletion and return with an error message
    //         return redirect()->route('store-manager.index')->with([
    //             'status' => false,
    //             'message' => 'Cannot Delete Store Manger With Stores In Inventory'
    //         ]);
    //     }

    //     // If no stores are associated, proceed with the deletion
    //     User::destroy($id);

    //     return redirect()->route('store-manager.index')->with([
    //         'status' => true,
    //         'message' => 'Store Manager Deleted Successfully'
    //     ]);
    // }

    public function destroy($id)
    {
        // Check if the store manager has any associated stores
        $storeCount = Store::where('storeManger_id', $id)->count();

        if ($storeCount > 0) {
            // If the store manager has stores, prevent deletion and return with an error message
            return redirect()->route('store-manager.index')->with([
                'status' => false,
                'message' => 'Cannot Delete Store Manager With Stores In Inventory'
            ]);
        }

        // If no stores are associated, proceed with the deletion
        User::destroy($id);

        return redirect()->route('store-manager.index')->with([
            'status' => true,
            'message' => 'Store Manager Deleted Successfully'
        ]);
    }


    // public function active($id)
    // {

    //     $data = User::find($id);
    //     $data->update(['is_active' => $data->is_active == 0 ? '1' : '0']);

    //     $message['first_name'] = $data->first_name;
    //     $message['last_name'] = $data->last_name;

    //     try {
    //         Mail::to($data->email)->send(new UserActivated($message));
    //         return redirect()->route('store-manager.index')->with(['status' => true, 'message' => 'Store Manager Activated Successfully']);
    //     } catch (\throwable $th) {
    //         dd($th->getMessage());
    //         return back()->with(['status' => false, 'message' => $th->getMessage()]);
    //     }
    //     return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    // }

    public function active(Request $request, $id)
    {
        // Find the user by ID
        $data = User::find($id);

        // Update the user's `is_active` status
        $data->update([
            'is_active' =>$request->is_active,
        ]);

        // Prepare message data
        $message['first_name'] = $data->first_name;
        $message['last_name'] = $data->last_name;

        // Handle `sendCredentials`
        $sendCredentials = $request->has('sendCredentials') && $request->sendCredentials == 1;
// return $sendCredentials;
        if ($sendCredentials) {
            // Generate a random password
            $password = random_int(10000000, 99999999);
            $message['email'] = $data->email;
            $message['password'] = $password;

            // Optionally store the password in the database (hashed)
            // $data->update(['password' => bcrypt($password)]);
        }

        try {
            // Send an email based on `sendCredentials`
            if ($sendCredentials == 1) {
                Mail::to($data->email)->send(new AccountActivatedNotification($message));
            } else {
                Mail::to($data->email)->send(new UserActivated($message));
            }

            return redirect()->route('store-manager.index')->with([
                'status' => true,
                'message' => 'Store Manager Activated Successfully',
            ]);
        } catch (\Throwable $th) {
            // Handle email sending errors
            return back()->with([
                'status' => false,
                'message' => 'Failed to send email: ' . $th->getMessage(),
            ]);
        }
    }



    public function deactive(Request $request, $id)
    {
        // return $request;
        $reason = $request->reason;
        $data = User::find($id);
        // return $data;

        $data->update([
            'is_active' => $request->is_active,
        ]);

        $message['reason'] = $reason;
        $message['first_name'] = $data->first_name;
        $message['last_name'] = $data->last_name;

        try {
            Mail::to($data->email)->send(new UserDeactivated($message));
            return redirect()->route('store-manager.index')->with(['status' => true, 'message' => 'Store Manager Deactivated Successfully']);
        } catch (\throwable $th) {
            dd($th->getMessage());
            return back()->with(['status' => false, 'message' => $th->getMessage()]);
        }
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    public function checkStores($id)
    {
        $storeCount = Store::where('storeManger_id', $id)->count();
        return response()->json(['hasStores' => $storeCount > 0]);
    }
}

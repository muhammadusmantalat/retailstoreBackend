<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RecommandBy;

class StoreManagerSignController extends Controller
{
    public function saveStoreManagerWithStore(Request $request)
{
    // return $request;
    try {
        // Validate incoming request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'store_name' => 'required|string|max:255',
            'store_address' => 'required|string',
            'store_phone_no' => 'required|string|max:15',
            'recommendendBy' => 'required|string|max:255',

        ]);

        // return $request;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/avator.png';
        }

        // Save store manager data
        $storeManager = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'user_type' => 'store_Manager',
            'is_active' => '0',
            'image' => $image,
        ]);
        // return $request;

        // Save store data
        $store = Store::create([
            'storeManger_id' => $storeManager->id,
            'store_name' => $request->store_name,
            'store_address' => $request->store_address,
            'store_phone_no' => $request->store_phone_no,
        ]);

        // return $store->id;

        $recommendedBy = RecommandBy::create([
            'store_manager_id' => $storeManager->id,
            'store_id' => $store->id,
            'recommendendBy' => $request->recommendendBy,
            'status' =>'active'
        ]);

        // return $storeManager;

        return response()->json([
            'status' => 'success',
            'message' => 'Store Manager and Store saved successfully!',
                'store_manager' => $storeManager,
                'store' => $store,
                'recommendedBy' => $recommendedBy,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong!',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}

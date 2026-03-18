<?php

namespace App\Http\Controllers\Managers;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Department;
use App\Models\AssignVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;

class ManagerStoresController extends Controller
{

    public function stores(Request $request)
    {
        
        $authId = Auth::guard('web')->id();
        $existingRecord = StoreManagerStoreDepartment::where('store_manager_id', $authId)
            ->first();
        if ($existingRecord) {
            // Update the existing record
            $existingRecord->update([
                'store_manager_id' => $authId,
                'store_id' => $request->store_id,
            ]);
        } else {
            // Create a new record
            StoreManagerStoreDepartment::create([
                'store_manager_id' => $authId,
                'store_id' => $request->store_id,
            ]);
        }

        return redirect()->to('manager/manager-dashboard');
    }
    public function dashboard()
    {
        $authId = Auth::guard('web')->id();
        $storeId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        // return $storeId->store_id;
        $vendor_count = AssignVendor::where('store_id' , $storeId->store_id)->count();
        // return $vendor_count;
        $product_count = Product::where('store_id' , $storeId->store_id)->count();
        // return $product_count;
        return view('managers.dashboard',compact('vendor_count','product_count'));
    }



    public function index(Request $request)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $departments = Department::where('store_id', $StoreId->store_id)->orderBy('id', 'desc')->get();
        return view('managers.departments.index', compact('departments'));
    }
}

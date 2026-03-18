<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::with('user')->orderBy('id', 'DESC')->get();
        return view('admin.store.index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeManagers =  User::where('user_type', 'store_Manager')->get();
        //  return  $storeManagers;
        return view('admin.store.create', compact('storeManagers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name.*' => 'required', // Validate each store name
            'store_address.*' => 'required', // Validate each store name
            'store_phone.*' => 'required', // Validate each store name
            'storeManager_id' => 'required'
        ]);
        $storeNames = $request->input('store_name');
        $storeAddresses = $request->input('store_address');
        $storePhones = $request->input('store_phone');

        $existingStores = Store::where('storeManger_id', $request->storeManager_id)
            ->whereIn('store_name', $request->store_name)
            ->get();

        // If any existing departments found, return with error message
        if ($existingStores->isNotEmpty()) {
            return redirect()->route('store-detail.index')->with(['status' => false, 'message' => 'Store already exist for this Store Manager.']);
        }

        // foreach ($storeNames as $storeName) {
        //     Store::create([
        //         'store_name' => $storeName,
        //         'storeManger_id' => $request->storeManager_id,
        //     ]);
        // }
        // return $request;

        for ($i = 0; $i < count($storeNames); $i++) {
            Store::create([
                'store_name' => $storeNames[$i],
                'store_address' => $storeAddresses[$i],
                'store_phone_no' => $storePhones[$i],
                'storeManger_id' => $request->storeManager_id,
            ]);
        }

        return redirect()->route('store-detail.index')->with(['status' => true, 'message' => 'Store added Successfully']);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $storeNames = Store::where('storeManger_id', $id)->with('user')->get();
        // return $storeNames;
        return view('admin.store.edit', compact('storeNames'));
    }




    // public function update(Request $request, $id)
    // {
    //     return $request;
    //     $storeNames = $request->input('store_name', []);


    //     $existingStores = Store::where('storeManger_id', $id)->get();

    //     // Update or delete existing stores
    //     foreach ($existingStores as $store) {
    //         $index = array_search($store->store_name, $storeNames);
    //         if ($index !== false) {
    //             $store->update([
    //                 'store_name' => $storeNames[$index],
    //             ]);
    //         } else {
    //             // Check if there are any departments associated with the store before deleting
    //             $departmentsCount = Department::where('store_id', $store->id)->count();
    //             if ($departmentsCount == 0) {
    //                 $store->delete();
    //             } else {
    //                 return redirect()->route('store-detail.index')->with([
    //                     'status' => false,
    //                     'message' => 'Cannot Delete Store With Departments In Inventory'
    //                 ]);
    //             }
    //         }
    //     }

    //     // Create new stores
    //     foreach ($storeNames as $storeName) {
    //         if (!Store::where('store_name', $storeName)->where('storeManger_id', $id)->exists()) {
    //             Store::create([
    //                 'store_name' => $storeName,
    //                 'storeManger_id' => $id,
    //             ]);
    //         }
    //     }

    //     return redirect()->route('store-detail.index')->with([
    //         'status' => true,
    //         'message' => 'Stores Updated Successfully'
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $storeNames = $request->input('store_name', []);
        $storeIds = $request->input('store_id', []);
        $storeAddresses = $request->input('store_address', []);
        $storePhones = $request->input('store_phone_no', []);

        $existingStores = Store::where('storeManger_id', $id)->get();

        // Update or delete existing stores
        foreach ($existingStores as $store) {
            $index = array_search($store->id, $storeIds);
            if ($index !== false) {
                // Update existing store with new data
                $store->update([
                    'store_name' => $storeNames[$index],
                    'store_address' => $storeAddresses[$index] ?? null,
                    'store_phone_no' => $storePhones[$index] ?? null,
                ]);
            } else {
                // Check if there are any departments associated with the store before deleting
                $departmentsCount = Department::where('store_id', $store->id)->count();
                if ($departmentsCount == 0) {
                    $store->delete();
                } else {
                    return redirect()->route('store-detail.index')->with([
                        'status' => false,
                        'message' => 'Cannot Delete Store With Departments In Inventory'
                    ]);
                }
            }
        }

        // Create new stores (if any new stores were added)
        foreach ($storeNames as $index => $storeName) {
            if (!Store::where('store_name', $storeName)->where('storeManger_id', $id)->exists()) {
                Store::create([
                    'store_name' => $storeName,
                    'storeManger_id' => $id,
                    'store_address' => $storeAddresses[$index] ?? null,
                    'store_phone_no' => $storePhones[$index] ?? null,
                ]);
            }
        }

        return redirect()->route('store-detail.index')->with([
            'status' => true,
            'message' => 'Stores Updated Successfully'
        ]);
    }


    public function checkDepartments($id)
    {
        $storeCount = Department::where('store_id', $id)->count();
        return response()->json(['hasStores' => $storeCount > 0]);
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($storeManagerId)
    // {
    //     $stores = Store::where('storeManger_id', $storeManagerId)->get();

    //     // Delete each store
    //     foreach ($stores as $store) {
    //         $store->delete();
    //     }
    //     return redirect()->route('store-detail.index')->with(['status' => true, 'message' => 'Store Deleted Successfully']);
    // }
}

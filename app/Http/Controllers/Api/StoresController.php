<?php

namespace App\Http\Controllers\Api;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;

class StoresController extends Controller
{
    public function getStores()
    {
        $authId = Auth::guard('api')->id();
        $stores = Store::where('storeManger_id', $authId)->get();
        // return $stores;
        return response()->json([
            'status'=>'success',
            'message'=>'stores get successfully',
            'stores' => $stores
        ]);
    }

    public function selectStore(Request $request)
    {
        $authId = Auth::guard('api')->id();
        $stores = Store::where('storeManger_id', $authId)->get();

        // Return the list of stores if no store_id is provided
        if (!$request->has('store_id')) {
            return response()->json(['stores' => $stores]);
        }

        // if(!$request->has(''))

        // Validate the store_id
        $request->validate([
            'store_id' => 'required|exists:stores,id',
        ]);

        // Check if the selected store is managed by the authenticated user
        $storeId = $request->input('store_id');
        // return $storeId;
        $store = Store::where('id', $storeId)->where('storeManger_id', $authId)->first();
        // return $store;


        if (!$store) {
            return response()->json(['error' => 'Store not found or you do not have permission to select this store.']);
        }

        // Update the current store selection for the user
        $currentStore = StoreManagerStoreDepartment::updateOrCreate(
            ['store_manager_id' => $authId],
            ['store_id' => $storeId]
        );

        return response()->json([
            'status'=>'success',
            'message'=>'selected store get successfully',
            'current_store' => $currentStore]);
    }
}

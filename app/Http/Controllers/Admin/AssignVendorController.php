<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Department;
use App\Models\AssignVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignVendorController extends Controller
{
    public function assignVendor($id)
    {
        $assignedVendors = AssignVendor::with('storeManager', 'store')->where('vendor_id' , $id)->orderBy('id', 'DESC')->get();


        return view('admin.assignVendor.index', compact('assignedVendors', 'id'));
    }

    public function create($id)
    {
        $storeManagers = User::where('user_type','store_Manager')->get();
        $stores = Store::all();
        $vendor = Vendor::findOrFail($id);

        return view('admin.assignVendor.create', compact('storeManagers', 'stores', 'vendor'));
    }

    public function assignVendorSave(Request $request)
    {
        // return $request;
        $request->validate([
            'storeManager' => 'required',
            'store' => 'required|array',
            'store.*' => 'required',
            'vendor_id' => 'required',
        ]);

        // return $request;
        $storeManagerId = $request->input('storeManager');
        $storeIds = $request->input('store');
        $vendorId = $request->input('vendor_id');

    //     $existingStores = AssignVendor::where('store_manager_id', $storeManagerId)->where('vendor_id' , $vendorId)
    //     ->whereIn('store_id', $request->store)
    //     ->get();

    // // If any existing departments found, return with error message
    // if ($existingStores->isNotEmpty()) {
    //     return redirect()->route('vendor-assign', ['id' => $request->vendor_id])->with(['status' => false, 'message' => 'This Store Already Assigned For This Vendor']);
    // }

        foreach ($storeIds as $storeId) {
            AssignVendor::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'store_id' => $storeId,
                'vendor_id' => $vendorId,
            ]);
        }
        return redirect()->route('vendor-assign', ['id' => $request->vendor_id])->with(['status' => true, 'message' => 'Store Manager & Store Assigned Successfully']);
    }

    public function assignVendorEdit($storeManagerId, $id)
    {
        // return $id;
        $assignments = AssignVendor::where('store_manager_id', $storeManagerId)->where('vendor_id' , $id)->get();
        $stores = Store::where('storeManger_id', $storeManagerId)->get();
        // return $stores;
        return view('admin.assignVendor.edit', compact('stores', 'assignments', 'storeManagerId', 'id'));
    }

    public function assignVendorUpdate(Request $request, $storeManagerId)
    {


        $assignmentsData = $request->input('assignments', []);

        foreach ($assignmentsData as $assignmentId => $assignmentData) {
            // Check if the store already exists for another assignment to avoid duplicates
            $existingAssignment = AssignVendor::where('store_id', $assignmentData['store_id'])
            ->where('id', '!=', $assignmentId) // Exclude the current assignment from the check
            ->first();


            if ($existingAssignment) {
                // If the store assignment already exists, delete the existing one
                $existingAssignment->delete();
            }

            // Fetch the assignment by ID and update it
            $assignment = AssignVendor::findOrFail($assignmentId);
            $assignment->update([
                'store_id' => $assignmentData['store_id'],
            ]);
        }
        return redirect()->route('vendor-assign', ['id' => $request->vendor_id])->with(['status' => true, 'message' => 'Store Manager & Store Updated Successfully']);
    }

    public function assignVendorDestroy(Request $request)
    {
        $id = $request->input('id'); // Yeh id wahi hogi jo aap AJAX request ke through bhej rahe hain.
        $result = AssignVendor::where('id', $id)->delete(); // Assuming 'id' is your primary key
        if ($result) {
            return response()->json(['success' => 'Assignment deleted successfully!']);
        } else {
            return response()->json(['error' => 'Error deleting assignment.'], 404);
        }
    }

    public function getStores(Request $request, $id)
    {
        try {
            $data = Store::where("storeManger_id", $id)->get();
            return response()->json([
                'data' => $data,
                'status' => 'Success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No StoreId Found ' . $e->getMessage(),
                'status' => 'Error'
            ], 500);
        }
    }
}

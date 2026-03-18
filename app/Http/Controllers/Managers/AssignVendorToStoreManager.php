<?php

namespace App\Http\Controllers\Managers;

use App\Models\Department;
use App\Models\AssignVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignVendorToDepartment;
use App\Models\StoreManagerStoreDepartment;

class AssignVendorToStoreManager extends Controller
{
    public function index($id)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $departments = AssignVendorToDepartment::where('store_id', $storeId)->where('vendor_id', $id)->get();

        $vendor = AssignVendor::where('store_id', $storeId)->where('vendor_id', $id)->first();
        // return $vendor;

        return view('managers.assignVendorToDepartment.index', compact('departments', 'id','vendor'));
    }

    public function create($id)
    {
        // return $id;
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $departments = Department::where('store_id', $storeId)->get();

        $vendor = AssignVendor::where('store_id', $storeId)->where('vendor_id', $id)->first();


        return view('managers.assignVendorToDepartment.create', compact('authId', 'storeId', 'id', 'departments','vendor'));
    }

    public function store(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'department.*' => 'required',
            'department' => 'required|array',
        ]);

        $storeManagerId = $request->input('store_manager_id');
        $storeId = $request->input('store_id');
        $vendorId = $request->input('vendor_id');
        $departmentIds = $request->input('department');

        // $existingDepartments = AssignVendorToDepartment::where('vendor_id', $vendorId)
        //     ->whereIn('department_id', $departmentIds)
        //     ->get();

        // // If any existing departments found, return with error message
        // if ($existingDepartments->isNotEmpty()) {
        //     // return $existingDepartments;
        //     return redirect()->route('manager.assignStoreManagerVendor', ['id' => $vendorId])->with(['status' => false, 'message' => 'Some Departments Already Assigned For This Vendor.']);
        // }
// return $departmentIds;
        foreach ($departmentIds as $departmentId) {
            AssignVendorToDepartment::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'vendor_id' => $vendorId,
                'store_id' => $storeId,
                'department_id' => $departmentId
            ]);
        }
        return redirect()->route('manager.assignStoreManagerVendor', ['id' => $vendorId])->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    }

    public function edit($id, $departmentId)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $specificStoreDepartments = Department::where('store_id', $storeId)->get();
        // return $specificStoreDepartments;
        $departments = AssignVendorToDepartment::where('vendor_id', $id)->where('department_id', $departmentId)->first();

        $vendor = AssignVendor::where('store_id', $storeId)->where('vendor_id', $id)->first();

        return view('managers.assignVendorToDepartment.edit', compact('departments', 'id', 'specificStoreDepartments', 'departmentId','vendor'));
    }

    public function update(Request $request, $id, $departmentId)
    {
        // return $request;

        // $request->validate([
        //     'department_id' => 'required|exists:departments,id', // Ensure the department exists
        //     'vendor_id' => 'required|exists:vendors,id', // Ensure the vendor exists
        // ]);
        // $assignment = AssignVendorToDepartment::where('vendor_id', $request->vendor_id)
        //     ->where('department_id', $departmentId)
        //     ->first();

        // // Update the department_id for the assignment
        // $assignment->department_id = $request->department_id;
        // $assignment->save();

        // // Redirect back or to another page with a success message
        // return redirect()->route('manager.assignStoreManagerVendor', ['id' => $id])->with(['status' => true, 'message' => 'Assigned Department Updated Successfully']);

        $request->validate([
            'department_id' => 'required',
                'vendor_id' => 'required'

        ]);

        $existingAssignment = AssignVendorToDepartment::where('vendor_id', $request->vendor_id)
            ->where('department_id', $request->department_id)
            ->first();

        if ($existingAssignment) {
            $existingAssignment->delete();
        }

        $assignment = AssignVendorToDepartment::where('vendor_id', $request->vendor_id)
            ->where('department_id', $departmentId)
            ->first();

        if ($assignment) {
            $assignment->department_id = $request->department_id;
            $assignment->save();
        } else {
            $assignment = new AssignVendorToDepartment();
            $assignment->vendor_id = $request->vendor_id;
            $assignment->department_id = $request->department_id;
            $assignment->save();
        }

     return redirect()->route('manager.assignStoreManagerVendor', ['id' => $id])->with(['status' => true, 'message' => 'Assigned Department Updated Successfully']);


    }

    public function destroy($id, $departmentId)
    {
        // Find the assignment you want to delete
        $assignment = AssignVendorToDepartment::where('vendor_id', $id)
            ->where('department_id', $departmentId)
            ->first();

        $assignment->delete();
        return redirect()->route('manager.assignStoreManagerVendor', ['id' => $id])->with(['status' => true, 'message' => 'Assigned Department Deleted Successfully']);
    }
}

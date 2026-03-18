<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Department;
use App\Models\AssignVendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StoreManagerStoreDepartment;
use App\Models\AssignVendorToDepartment as vendorDepartment;

class AssignVendorToDepartment extends Controller
{
    public function vendorDepartments($storeManagerId, $id)
    {
        // $assignedVendors = vendorDepartment::with('store', 'department')->orderBy('id', 'DESC')->get();
        $storesId = StoreManagerStoreDepartment::where('store_manager_id', $storeManagerId)->first();
        // $storeId = $storesId->store_id;
        // $vendors = Vendor::with('assignVendors')->where('store_id', $StoreId->store_id)->orderBy('id', 'DESC')->get();
        $departments = vendorDepartment::where('store_manager_id', $storeManagerId)->where('vendor_id', $id)
            ->orderBy('id', 'DESC')
            ->get();
        // return $vendors;
        return view('admin.assignVendorToDepartment.index', compact('departments', 'id', 'storeManagerId'));
    }

    public function vendorDepartmentCreate($storeManagerId, $id)
    {
        $storeManagers = AssignVendor::with('store')->where('store_manager_id', $storeManagerId)->where('vendor_id', $id)->get();
        // return $storeManagers;
        return view('admin.assignVendorToDepartment.create', compact('storeManagers', 'storeManagerId', 'id'));
    }

    public function vendorDepartmentSave(Request $request, $storeManagerId, $id)
    {
        // return $request;
        $request->validate([

            'store' => 'required',
            'department' => 'required|array',
            'department.*' => 'required',
            'assignVendorId' => 'required'
        ]);
        // return $request;

        $storeIds = $request->input('store');
        $departmentIds = $request->input('department');
        $assignVendorId = $request->input('assignVendorId');
        // return $assignVendorId;

        // $existingDepartments = vendorDepartment::where('vendor_id', $id)
        //     ->whereIn('department_id', $departmentIds)
        //     ->get();

        // // If any existing departments found, return with error message
        // if ($existingDepartments->isNotEmpty()) {
        //     return redirect()->route('vendor-departments', ['storeManagerId' => $storeManagerId, 'id' => $id])->with(['status' => false, 'message' => 'This Department Already Assigned For This Vendor.']);
        // }
        foreach ($departmentIds as $departmentId) {
            vendorDepartment::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'vendor_id' => $id,
                'store_id' => $storeIds,
                'department_id' => $departmentId,
                'assignVendor_id' =>$assignVendorId
            ]);
        }
        return redirect()->route('vendor-departments', ['storeManagerId' => $storeManagerId, 'id' => $id])
            ->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    }

    public function vendorDepartmentEdit($storeManagerId, $id, $storeId)
    {
        // return $request;
        $assignments = vendorDepartment::where('store_id', $storeId)->where('vendor_id', $id)->get();
        $departments = Department::where('store_id', $storeId)->get();

        return view('admin.assignVendorToDepartment.edit', compact('assignments', 'departments', 'storeManagerId', 'id', 'storeId'));
    }

    public function vendorDepartmentUpdate(Request $request, $storeManagerId)
    {


        $assignmentsData = $request->input('assignments', []);

    foreach ($assignmentsData as $assignmentId => $assignmentData) {
        // Check if the department is already assigned to another vendor
        $existingAssignment = VendorDepartment::where('department_id', $assignmentData['department_id'])
            ->where('id', '!=', $assignmentId) // Exclude the current assignment from the check
            ->first();

        if ($existingAssignment) {
            // If the department is already assigned, delete the existing one
            $existingAssignment->delete();
        }

        // Fetch the assignment by ID and update it
        $assignment = VendorDepartment::findOrFail($assignmentId);
        $assignment->update([
            'department_id' => $assignmentData['department_id'],
        ]);
    }
        return redirect()->route('vendor-departments', ['storeManagerId' => $storeManagerId, 'id' => $request->vendor_id])
            ->with(['status' => true, 'message' => 'Department Updated Successfully']);
    }

    public function vendorDepartmentDestroy(Request $request)
    {
        $id = $request->input('id'); // Yeh id wahi hogi jo aap AJAX request ke through bhej rahe hain.
        $result = VendorDepartment::where('id', $id)->delete(); // Assuming 'id' is your primary key
        if ($result) {
            return response()->json(['success' => 'Assignment deleted successfully!']);
        } else {
            return response()->json(['error' => 'Error deleting assignment.'], 404);
        }
    }



    public function getDepartments(Request $request, $id)
    {
        try {
            $data = Department::where("store_id", $id)->get();
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

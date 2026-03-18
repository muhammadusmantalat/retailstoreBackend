<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ProductAssign;
use App\Http\Controllers\Controller;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;
use App\Models\StoreManagerStoreDepartment;

class ProductAssignToDepartmentController extends Controller
{
    public function index($storeManagerId,$storeId,$productId)
    {
        $departments = ProductAssignToDepartment::where('store_manager_id', $storeManagerId)->where('store_id', $storeId)->where('product_id', $productId)
            ->orderBy('id', 'DESC')
            ->get();
            // return $departments;
        return view('admin.productAssignToDepartment.index',compact('storeManagerId','storeId','productId','departments'));
    }

    public function create($storeManagerId,$storeId,$productId)
    {
        $storeDepartments = Department::where('store_manager_id', $storeManagerId)->where('store_id', $storeId)->get();
        // return $storeDepartments;

        return view('admin.productAssignToDepartment.create', compact('storeDepartments', 'storeManagerId', 'storeId','productId'));
    }

    public function Store(Request $request, $storeManagerId,$storeId,$productId)
    {
        // return $request;
        $request->validate([
            'department' => 'required|array',
            'department.*' => 'required',
        ]);

        $departmentIds = $request->input('department');

        foreach ($departmentIds as $departmentId) {
            ProductAssignToDepartment::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'store_id' => $storeId,
                'department_id' => $departmentId,
                'product_id' => $productId
            ]);
                }
        return redirect()->route('products-departments', ['storeManagerId' => $storeManagerId, 'storeId' => $storeId, 'productId' => $productId ])
            ->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    }

    public function edit($storeManagerId, $storeId, $productId , $id)
{
    $assignments = ProductAssignToDepartment::where('store_manager_id', $storeManagerId)
        ->where('store_id', $storeId)
        ->where('product_id', $productId)
        ->where('id', $id)
        ->get();
        // return $assignments;

    $departments = Department::where('store_manager_id', $storeManagerId)
        ->where('store_id', $storeId)
        ->get();

        // return $departments;
    return view('admin.productAssignToDepartment.edit', compact('assignments', 'departments', 'storeManagerId', 'productId', 'storeId','id'));
}


    // public function update(Request $request, $storeManagerId)
    // {
    //     // return $request;
    //     $assignmentsData = $request->input('assignments', []);
    //     // return $assignmentsData;
    //     foreach ($assignmentsData as $assignmentId => $assignmentData) {
    //         $assignment = ProductAssignToDepartment::findOrFail($assignmentId);
    //         $assignment->update([
    //             'department_id' => $assignmentData['department_id'],
    //         ]);
    //     }
    //     return redirect()->route('products-departments', ['storeManagerId' => $storeManagerId, 'storeId' => $request->store_id, 'productId' => $request->product_id ])
    //         ->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    // }

    public function update(Request $request, $storeManagerId)
{
    $assignmentsData = $request->input('assignments', []);

    foreach ($assignmentsData as $assignmentId => $assignmentData) {
        // Check if the department already exists
        $existingDepartment = ProductAssignToDepartment::where('department_id', $assignmentData['department_id'])
            ->where('id', '!=', $assignmentId) // Make sure to exclude the current assignment from the check
            ->first();

        if ($existingDepartment) {
            // If the department already exists, delete the existing assignment
            $existingDepartment->delete();
        }

        // Fetch the assignment by ID and update it
        $assignment = ProductAssignToDepartment::findOrFail($assignmentId);
        $assignment->update([
            'department_id' => $assignmentData['department_id'],
        ]);
    }

    return redirect()->route('products-departments', [
        'storeManagerId' => $storeManagerId,
        'storeId' => $request->store_id,
        'productId' => $request->product_id
    ])->with(['status' => true, 'message' => 'Department Updated Successfully']);
}


    public function destroy(Request $request)
    {
        $id = $request->input('id'); // Yeh id wahi hogi jo aap AJAX request ke through bhej rahe hain.
        $result = ProductAssignToDepartment::where('id', $id)->delete(); // Assuming 'id' is your primary key
        if ($result) {
            return response()->json(['success' => 'Assignment deleted successfully!']);
        } else {
            return response()->json(['error' => 'Error deleting assignment.'], 404);
        }
    }





    public function getProductDepartments(Request $request, $id)
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

<?php

namespace App\Http\Controllers\Managers;

use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToDepartment;
use App\Models\StoreManagerStoreDepartment;

class ProductDepartmentController extends Controller
{
   public function index($id)
   {
    $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $departments = ProductAssignToDepartment::where('store_id', $storeId)->where('product_id', $id)->with('product')->get();

        $product = Product::where('store_id', $storeId)->where('id', $id)->first();

        // return $product;
        return view('managers.productAssignToDepartment.index', compact('departments', 'id','product'));
    }

    public function create($id)
    {
        // return $id;
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $departments = Department::where('store_id', $storeId)->get();
        $product = Product::where('store_id', $storeId)->where('id', $id)->first();

        return view('managers.productAssignToDepartment.create', compact('authId', 'storeId', 'id', 'departments','product'));
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
        $productId = $request->input('product_id');
        $departmentIds = $request->input('department');

        foreach ($departmentIds as $departmentId) {
            ProductAssignToDepartment::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'product_id' => $productId,
                'store_id' => $storeId,
                'department_id' => $departmentId
            ]);
        }
        return redirect()->route('manager.ProductsDepartments', ['id' => $productId])->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    }

    public function edit($id,$departmentId)
    {
        // return $id;
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $specificStoreDepartments = Department::where('store_id', $storeId)->get();
        // return $specificStoreDepartments;
        $departments = ProductAssignToDepartment::where('product_id', $id)->where('department_id', $departmentId)->first();
        $product = Product::where('store_id', $storeId)->where('id', $id)->first();

        return view('managers.productAssignToDepartment.edit',compact('specificStoreDepartments','departments','id','departmentId','product'));
    }

    public function update(Request $request, $id, $departmentId)
    {
        // // return $request;
        // $request->validate([
        //     'department_id' => 'required|exists:departments,id', // Ensure the department exists
        //     'product_id' => 'required|exists:products,id', // Ensure the vendor exists
        // ]);
        // $assignment = ProductAssignToDepartment::where('product_id', $request->product_id)
        //     ->where('department_id', $departmentId)
        //     ->first();

        // // Update the department_id for the assignment
        // $assignment->department_id = $request->department_id;
        // $assignment->save();

        // return redirect()->route('manager.ProductsDepartments', ['id' => $id])->with(['status' => true, 'message' => 'Assigned Department Updated Successfully']);
        $request->validate([
            'department_id' => 'required', // Ensure the department exists
            'product_id' => 'required', // Ensure the product exists
        ]);

        // Check if the product is already assigned to the department the user is trying to assign
        $existingAssignment = ProductAssignToDepartment::where('product_id', $request->product_id)
            ->where('department_id', $request->department_id)
            ->first();

        // If the product is already assigned to this department, delete the existing assignment
        if ($existingAssignment) {
            $existingAssignment->delete();
        }

        // Check if there is any other department assignment for the product to update, else create a new assignment
        $assignment = ProductAssignToDepartment::where('product_id', $request->product_id)
            ->where('department_id', $departmentId)
            ->first();

        if ($assignment) {
            // Update the department_id for the assignment
            $assignment->department_id = $request->department_id;
            $assignment->save();
        } else {
            // No existing assignment found for this product and departmentId, create a new assignment
            $assignment = new ProductAssignToDepartment();
            $assignment->product_id = $request->product_id;
            $assignment->department_id = $request->department_id;
            $assignment->save();
        }

        return redirect()->route('manager.ProductsDepartments', ['id' => $id])->with(['status' => true, 'message' => 'Department Updated Successfully']);

    }

    public function destory($id, $departmentId)
    {
        // Find the assignment you want to delete
        $assignment = ProductAssignToDepartment::where('product_id', $id)
            ->where('department_id', $departmentId)
            ->first();

        $assignment->delete();
        return redirect()->route('manager.ProductsDepartments', ['id' => $id])->with(['status' => true, 'message' => 'Department Deleted Successfully']);
    }


}

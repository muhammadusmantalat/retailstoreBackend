<?php

namespace App\Http\Controllers\Managers;

use App\Models\User;
use App\Models\Store;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToDepartment;
use App\Models\StoreManagerStoreDepartment;

class ManagerDepartmentController extends Controller
{
    public function create()
    {

        $authId = Auth::guard('web')->id();
        $storeManagerDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        return view('managers.departments.create', compact('storeManagerDepartment'));
    }

    // public function store(Request $request)
    // {
    //     // return $request;
    //     $authId = Auth::guard('web')->id();

    //     $validatedData = $request->validate([
    //         'department_name' => 'required|array',
    //         'department_name.*' => 'required|string|max:255',
    //         'store_id' => 'required|exists:store_manager_store_departments,store_id,store_manager_id,' . $authId,
    //         'tax_status' => 'required|array',
    //         'tax_status.*' => 'required|in:0,1' // Validate as 0 or 1
    //     ]);

    //     foreach ($validatedData['department_name'] as $index => $departmentName) {
    //         $taxStatus = $validatedData['tax_status'][$index] ?? null; // Get the corresponding tax status

    //         if ($taxStatus !== null) {
    //             Department::firstOrCreate([
    //                 'department_name' => $departmentName,
    //                 'store_id' => $validatedData['store_id'],
    //                 'store_manager_id' => $authId,
    //                 'tax_status' => $taxStatus
    //             ]);
    //         }
    //     }
    //     // $department = new Department();
    //     // $department->department_name = $departmentName;
    //     // $department->store_id = $validatedData['store_id'];
    //     // $department->store_manager_id = $authId;
    //     // $department->save();


    //     return redirect()->route('manager.manager-store-department')->with(['status' => true, 'message' => 'Department Added Successfully']);
    // }

    public function store(Request $request)
    {
        $authId = Auth::guard('web')->id();

        $validatedData = $request->validate([
            'department_name' => 'required|array',
            'department_name.*' => 'required|string|max:255',
            'store_id' => 'required|exists:store_manager_store_departments,store_id,store_manager_id,' . $authId,
            'tax_status' => 'required|array',
            'tax_status.*' => 'required|in:0,1', // Validate as 0 or 1
            'image' => 'array',
            'image.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ]);

        foreach ($validatedData['department_name'] as $index => $departmentName) {
            $taxStatus = $validatedData['tax_status'][$index] ?? null; // Get the corresponding tax status

            if ($taxStatus !== null) {
                // Handle image upload if present
                $imagePath = null;
                if ($request->hasFile('image.' . $index)) {
                    $file = $request->file('image.' . $index);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('admin/assets/images/departments'), $filename);
                    $imagePath = 'admin/assets/images/departments/' . $filename;
                }

                // Create the department with or without an image
                Department::create([
                    'department_name' => $departmentName,
                    'store_id' => $validatedData['store_id'],
                    'store_manager_id' => $authId,
                    'tax_status' => $taxStatus,
                    'image' => $imagePath,
                ]);
            }
        }

        return redirect()->route('manager.manager-store-department')->with(['status' => true, 'message' => 'Department Added Successfully']);
    }



    public function edit($id)
    {
        // return $id;
        $departments = Department::find($id);
        // return  $departments;
        return view('managers.departments.edit', compact('departments'));
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'department_name' => 'required',
            'tax_status' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $departments = Department::find($id);

        $departments->update([
            'department_name' => $request->department_name,
            'tax_status' => $request->tax_status,
            'image' => $request->image
        ]);
        return redirect()->route('manager.manager-store-department')->with(['status' => true, 'message' => 'Department Updated Successfully']);
    }

    public function destroy($id)
    {
        Department::destroy($id);
        return redirect()->route('manager.manager-store-department')->with(['status' => true, 'message' => 'Department Deleted Successfully']);
    }

    public function checkProducts($id)
    {
        $productCount = ProductAssignToDepartment::where('department_id', $id)->count();
        return response()->json(['hasProducts' => $productCount > 0]);
    }
}

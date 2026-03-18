<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;


class DepartmentsController extends Controller
{
    public function departments($id)
    {
        // return $id;
        $storeManager = User::find($id);
        $departments = Department::with('store')->where('store_manager_id', $id)->orderBy('id', 'desc')->get();
        // return $departments;
        return view('admin.department.index', compact('storeManager', 'departments'));
    }


    public function new($id)
    {
        $storeManager = User::find($id); // Assuming $store_id is the ID of the store
        $stores = Store::where('storeManger_id', $id)->orderBy('id', 'desc')->get(); // Assuming $store_id is the ID of the store
        return view('admin.department.create', compact('storeManager', 'stores'));
    }



    // public function save(Request $request)
    // {
    //     return $request;
    //     // Validate the request
    //     $request->validate([
    //         'department_name.*' => 'required',
    //         'image.*' => 'required',
    //         'store_id' => 'required',
    //         'tax_status.*' => 'required'
    //     ]);

    //     $departmentNames = $request->input('department_name');
    //     $taxStatuses = $request->input('tax_status');

    //     // Check for existing departments
    //     $existingStores = Department::where('store_id', $request->store_id)
    //         ->whereIn('department_name', $departmentNames)
    //         ->get();

    //     // If any existing departments found, return with error message
    //     if ($existingStores->isNotEmpty()) {
    //         return redirect()->route('departments', ['id' => $request->store_manager_id])
    //             ->with(['status' => false, 'message' => 'Department already exist for this Store.']);
    //     }

    //     // Iterate over department names and tax statuses
    //     foreach ($departmentNames as $index => $departmentName) {
    //         Department::create([
    //             'store_manager_id' => $request->store_manager_id,
    //             'store_id' => $request->store_id,

    //             'department_name' => $departmentName,
    //             'tax_status' => $taxStatuses[$index] // Use index to match tax status with department name
    //         ]);
    //     }

    //     return redirect()->route('departments', ['id' => $request->store_manager_id])
    //         ->with(['status' => true, 'message' => 'Department added Successfully']);
    // }

    public function save(Request $request)
    {
        // Validate the request
        $request->validate([
            'department_name.*' => 'required',
            'image.*' => 'required|image',
            'store_id' => 'required',
            'tax_status.*' => 'required'
        ]);

        $departmentNames = $request->input('department_name');
        $taxStatuses = $request->input('tax_status');

        // Check for existing departments
        $existingStores = Department::where('store_id', $request->store_id)
            ->whereIn('department_name', $departmentNames)
            ->get();

        // If any existing departments found, return with error message
        if ($existingStores->isNotEmpty()) {
            return redirect()->route('departments', ['id' => $request->store_manager_id])
                ->with(['status' => false, 'message' => 'Department already exists for this Store.']);
        }

        // Iterate over department names, tax statuses, and images
        foreach ($departmentNames as $index => $departmentName) {
            $imagePath = null;

            // Handle image upload
            if ($request->hasFile('image.' . $index)) {
                $file = $request->file('image.' . $index);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/assets/images/departments'), $filename);
                $imagePath = 'public/admin/assets/images/departments/' . $filename;
                // return $imagePath;
            }

            Department::create([
                'store_manager_id' => $request->store_manager_id,
                'store_id' => $request->store_id,
                'department_name' => $departmentName,
                'tax_status' => $taxStatuses[$index], // Use index to match tax status with department name
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('departments', ['id' => $request->store_manager_id])
            ->with(['status' => true, 'message' => 'Department Added Successfully']);
    }




    public function editDepartment($id)
    {
        $stores = Store::find($id);
        $departments = Department::where('store_id', $id)->get();
        return view('admin.department.edit', compact('departments', 'stores'));
    }




//     public function updateDepartment(Request $request, $id)
// {
//     // Validate request data
//     $request->validate([
//         'department_name.*' => 'required|string|max:255',
//         'tax_status.*' => 'required|in:0,1',
//         'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
//     ]);

//     $departmentNames = $request->input('department_name', []);
//     $taxStatuses = $request->input('tax_status', []);
//     $images = $request->file('image', []);

//     // Get existing departments for the given store
//     $departments = Department::where('store_id', $id)->get();

//     // Update existing departments
//     foreach ($departments as $department) {
//         $index = array_search($department->department_name, $departmentNames);
//         if ($index !== false) {
//             $updateData = [
//                 'department_name' => $departmentNames[$index],
//                 'tax_status' => $taxStatuses[$index],
//             ];

//             // Handle image upload if present
//             if (isset($images[$index]) && $images[$index] instanceof \Illuminate\Http\UploadedFile) {
//                 $imagePath = $images[$index]->store('departments', 'public');
//                 $updateData['image'] = $imagePath;
//             }

//             $department->update($updateData);
//         } else {
//             // Check if there are any products in the department before deleting
//             $productsCount = ProductAssignToDepartment::where('department_id', $department->id)->count();
//             if ($productsCount == 0) {
//                 $department->delete();
//             } else {
//                 return redirect()->route('departments', ['id' => $request->store_manager_id])
//                     ->with(['status' => false, 'message' => 'Cannot Delete Department With Products In Inventory']);
//             }
//         }
//     }

//     // Create new departments
//     foreach ($departmentNames as $index => $departmentName) {
//         if (!Department::where('department_name', $departmentName)->where('store_id', $id)->exists()) {
//             $newDepartmentData = [
//                 'department_name' => $departmentName,
//                 'tax_status' => $taxStatuses[$index],
//                 'store_id' => $id,
//                 'store_manager_id' => $request->store_manager_id,
//             ];

//             // Handle image upload if present
//             if (isset($images[$index]) && $images[$index] instanceof \Illuminate\Http\UploadedFile) {
//                 $imagePath = $images[$index]->store('departments', 'public');
//                 $newDepartmentData['image'] = $imagePath;
//             }

//             Department::create($newDepartmentData);
//         }
//     }

//     return redirect()->route('departments', ['id' => $request->store_manager_id])
//         ->with(['status' => true, 'message' => 'Departments updated successfully']);
// }

public function updateDepartment(Request $request, $id)
{
    // Validate request data
    $request->validate([
        'department_name.*' => 'required|string|max:255',
        'tax_status.*' => 'required|in:0,1',
        'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
    ]);

    $departmentNames = $request->input('department_name', []);
    $taxStatuses = $request->input('tax_status', []);
    $images = $request->file('image', []);

    // Get existing departments for the given store
    $departments = Department::where('store_id', $id)->get();

    // Update existing departments
    foreach ($departments as $department) {
        $index = array_search($department->department_name, $departmentNames);
        if ($index !== false) {
            $updateData = [
                'department_name' => $departmentNames[$index],
                'tax_status' => $taxStatuses[$index],
            ];

            // Handle image upload if present
            if ($request->hasFile('image.' . $index)) {
                $file = $request->file('image.' . $index);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/assets/images/departments'), $filename);
                $imagePath = 'public/admin/assets/images/departments/' . $filename; // Remove 'public/' from the path
                $updateData['image'] = $imagePath;
            }

            $department->update($updateData);
        } else {
            // Check if there are any products in the department before deleting
            $productsCount = ProductAssignToDepartment::where('department_id', $department->id)->count();
            if ($productsCount == 0) {
                $department->delete();
            } else {
                return redirect()->route('departments', ['id' => $request->store_manager_id])
                    ->with(['status' => false, 'message' => 'Cannot delete department with products in inventory']);
            }
        }
    }

    // Create new departments
    foreach ($departmentNames as $index => $departmentName) {
        if (!Department::where('department_name', $departmentName)->where('store_id', $id)->exists()) {
            $newDepartmentData = [
                'department_name' => $departmentName,
                'tax_status' => $taxStatuses[$index],
                'store_id' => $id,
                'store_manager_id' => $request->store_manager_id,
            ];

            // Handle image upload if present
            if ($request->hasFile('image.' . $index)) {
                $file = $request->file('image.' . $index);
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('admin/assets/images/departments'), $filename);
                $imagePath = 'admin/assets/images/departments/' . $filename; // Remove 'public/' from the path
                $newDepartmentData['image'] = $imagePath;
            }

            Department::create($newDepartmentData);
        }
    }

    return redirect()->route('departments', ['id' => $request->store_manager_id])
        ->with(['status' => true, 'message' => 'Departments Updated Successfully']);
}





    public function destroy($id)
    {
        Department::where('store_id', $id)->delete();
        return redirect()->back()->with(['status' => true, 'message' => 'Department Deleted Successfully']);
    }

    public function checkProducts($id)
    {
        $productCount = ProductAssignToDepartment::where('department_id', $id)->count();
        return response()->json(['hasProducts' => $productCount > 0]);
    }
}

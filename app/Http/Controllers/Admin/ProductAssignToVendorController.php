<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductAssignToVendor;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;
use App\Models\StoreManagerStoreDepartment;

class ProductAssignToVendorController extends Controller
{
    public function index($productId, $storeManagerId, $storeId)
    {
        $assignedProducts = ProductAssignToVendor::with(['vendor', 'departments'])
            ->where('store_manager_id', $storeManagerId)
            ->where('store_id', $storeId)
            ->where('product_id', $productId)->orderBy('vendor_id', 'DESC')
            ->get();
        return view('admin.productAssignToVendor.index', compact('productId', 'storeManagerId', 'storeId', 'assignedProducts'));
    }

    public function create($productId, $storeManagerId, $storeId)
    {
        //  return $productId;
        $StoreManagerId = User::find($storeManagerId);
        $storeManagerId = $StoreManagerId->id;
        // return $storeManagerId;

        $StoreId = Store::find($storeId);
        $storeId = $StoreId->id;
        // return $storeId;

        $departmentsWithProducts = ProductAssignToDepartment::where('product_id', $productId)
            ->get();

        // return $departmentsWithProducts;

        $departmentIds = $departmentsWithProducts->pluck('department_id')->unique();
        // return $departmentIds;

        $vendorAssignments = AssignVendorToDepartment::with('vendors')->where('store_manager_id', $storeManagerId)
            ->where('store_id', $storeId)->whereIn('department_id', $departmentIds)
            ->get()
            ->unique('vendor_id');


        // return $vendorAssignments;

        return view('admin.productAssignToVendor.create', compact('storeManagerId', 'storeId', 'productId', 'vendorAssignments', 'StoreManagerId', 'StoreId'));
    }

    // public function store(Request $request, $productId)
    // {

    //     $request->validate([
    //         'product_id' => 'required',
    //         'Wholesaler_id' => 'required|array',
    //         'Wholesaler_id.*' => 'required',
    //         'department' => 'required|array',
    //         'department.*' => 'required',
    //         'price' => 'required|array',
    //         'price.*' => 'required|numeric|min:0',
    //         'assignments' => 'array', // Validate if 'assignments' is an array
    //         'assignments.*.Wholesaler_id' => 'required',
    //         'assignments.*.department.*' => 'required',
    //         'assignments.*.price' => 'required|numeric|min:0',
    //         ]);
    //     // return $request;

    //     $productId = $request->input('product_id');
    //     $storeManagerId = $request->input('store_manager_id');
    //     $storeId = $request->input('store_id');

    //     // Retrieve 'store_manager_id' and 'store_id' once
    //     $assignments = collect($request->assignments ?? []);

    //     // Process the initial set of vendor, department, and price
    //     $vendors = is_array($request->Wholesaler_id) ? $request->Wholesaler_id : [];
    //     $departments = is_array($request->department) ? $request->department : [];
    //     $prices = is_array($request->price) ? $request->price : [];

    //     foreach ($vendors as $index => $vendorId) {
    //         $price = $prices[$index] ?? null; // Use null or any default if no price is set
    //         foreach ($departments as $departmentId) {
    //             ProductAssignToVendor::updateOrCreate([
    //                 'store_manager_id' => $storeManagerId,
    //                 'store_id' => $storeId,
    //                 'Wholesaler_id' => $vendorId,
    //                 'product_id' => $productId,
    //                 'department_id' => $departmentId,
    //             ], [
    //                 'product_price' => $price,
    //             ]);
    //         }
    //     }

    //     // Process additional assignments
    //     $assignments->each(function ($assignment) use ($storeManagerId, $storeId, $productId) {
    //         $vendorId = $assignment['vendor_id'];
    //         $price = $assignment['price'];

    //         collect($assignment['department'])->each(function ($departmentId) use ($storeManagerId, $storeId, $vendorId, $productId, $price) {
    //             ProductAssignToVendor::updateOrCreate([
    //                 'store_manager_id' => $storeManagerId,
    //                 'store_id' => $storeId,
    //                 'vendor_id' => $vendorId,
    //                 'product_id' => $productId,
    //                 'department_id' => $departmentId,
    //             ], [
    //                 'product_price' => $price,
    //             ]);
    //         });
    //     });

    //     return redirect()->route('products-assignVendor', ['productId' => $productId,'storeManagerId' => $storeManagerId, 'storeId' => $storeId ])->with(['status' => true, 'message' => 'Product Assigned Successfully']);
    // }
    public function store(Request $request, $productId)
    {
        // Validate the incoming request data
        $request->validate([
            'product_id' => 'required',
            'Wholesaler_id' => 'required|array',
            'Wholesaler_id.*' => 'required|integer',
            'department' => 'required|array',
            'department.*' => 'required|integer',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'assignments' => 'array', // Validate if 'assignments' is an array
            'assignments.*.Wholesaler_id' => 'required|integer',
            'assignments.*.department' => 'required|array',
            'assignments.*.department.*' => 'required|integer',
            'assignments.*.price' => 'required|numeric|min:0',
        ]);

        // Retrieve 'product_id', 'store_manager_id' and 'store_id' from the request
        $productId = $request->input('product_id');
        $storeManagerId = $request->input('store_manager_id');
        $storeId = $request->input('store_id');

        // Retrieve 'assignments' from the request
        $assignments = collect($request->input('assignments', []));

        // Process the initial set of vendor, department, and price
        $vendors = $request->input('Wholesaler_id', []);
        $departments = $request->input('department', []);
        $prices = $request->input('price', []);

        foreach ($vendors as $index => $vendorId) {
            $price = $prices[$index] ?? null; // Use null or any default if no price is set
            foreach ($departments as $departmentId) {
                ProductAssignToVendor::updateOrCreate([
                    'store_manager_id' => $storeManagerId,
                    'store_id' => $storeId,
                    'vendor_id' => $vendorId, // Use 'vendor_id' for database column
                    'product_id' => $productId,
                    'department_id' => $departmentId,
                ], [
                    'product_price' => $price,
                ]);
            }
        }

        // Process additional assignments
        $assignments->each(function ($assignment) use ($storeManagerId, $storeId, $productId) {
            $vendorId = $assignment['Wholesaler_id']; // Use 'Wholesaler_id' from the request
            $price = $assignment['price'];

            collect($assignment['department'])->each(function ($departmentId) use ($storeManagerId, $storeId, $vendorId, $productId, $price) {
                ProductAssignToVendor::updateOrCreate([
                    'store_manager_id' => $storeManagerId,
                    'store_id' => $storeId,
                    'vendor_id' => $vendorId, // Use 'vendor_id' for database column
                    'product_id' => $productId,
                    'department_id' => $departmentId,
                ], [
                    'product_price' => $price,
                ]);
            });
        });

        return redirect()->route('products-assignVendor', [
            'productId' => $productId,
            'storeManagerId' => $storeManagerId,
            'storeId' => $storeId
        ])->with(['status' => true, 'message' => 'Product Assigned Successfully']);
    }




    public function edit($id, $vendorId, $productId)

    {
        $assignedProduct = ProductAssignToVendor::find($id);
        // return $assignedProduct;

        $storeManager = $assignedProduct->store_manager_id;
        $store_manager_id = User::find($storeManager);
        $storeManagerId = $store_manager_id->id;
        $storeManagerName = $store_manager_id->first_name . " " . $store_manager_id->last_name;
        // return $storeManagerId;

        $store = $assignedProduct->store_id;
        $store_id = Store::find($store);
        $storeId = $store_id->id;
        $storeName = $store_id->store_name;
        // return $storeName;

        $departments_id = AssignVendorToDepartment::where('vendor_id', $vendorId)->pluck('department_id');

        $assignedProducts = ProductAssignToVendor::where('vendor_id', $id)->first();
        // return $assignedProducts;

        $departments = Department::whereIn('id', function ($query) use ($departments_id, $productId) {
            $query->select('department_id')
                ->from('product_assign_to_departments')
                ->whereIn('department_id', $departments_id)
                ->where('product_id', $productId);
        })->get();

        // return $departments;
        $vendorName = Vendor::where('id', $vendorId)->value('vendor_name');
        $id = $assignedProduct->id;
        //    return $id;
        // return $vendorName;


        return view('admin.productAssignToVendor.edit', compact('storeManagerId', 'storeManagerName', 'storeId', 'storeName', 'vendorId', 'productId', 'vendorName', 'assignedProduct', 'departments', 'id'));
    }


    public function update(Request $request, $id, $vendorId, $productId)
    {
        $request->validate([
            'department_id' => 'required',
            'price' => 'required',
        ]);
        // return $request;

        $storeManagerId = $request->input('store_manager_id');
        $storeId = $request->input('store_id');
        $vendor = $request->input('vendor_id');
        $productId = $request->input('product_id');
        $departmentId = is_array($request->department_id) ? $request->department_id[0] : $request->department_id;
        $prices = $request->input('price');

        // Check for an existing record with the same department_id (that is not the current record)
        $existingRecord = ProductAssignToVendor::where('department_id', $departmentId)
            ->where('id', '<>', $id) // Exclude the current record
            ->first();

        if ($existingRecord) {
            $existingRecord->delete(); // Delete if found
        }

        // Proceed to find and update the current ProductAssignToVendor
        $productAssignToVendor = ProductAssignToVendor::find($id);

        if (!$productAssignToVendor) {
            return back()->with(['status' => false, 'message' => 'Product assignment not found.']);
        }

        $productAssignToVendor->update([
            'store_manager_id' => $storeManagerId,
            'store_id' => $storeId,
            'vendor_id' => $vendor,
            'product_id' => $productId,
            'department_id' => $departmentId,
            'product_price' => $prices,
        ]);

        return redirect()->route('products-assignVendor', ['productId' => $productId, 'storeManagerId' => $storeManagerId, 'storeId' => $storeId])
            ->with(['status' => true, 'message' => 'Assigned Product Updated Successfully']);
    }

    // public function destroy(Request $request)
    // {
    //     $id = $request->input('id');
    //     $result = ProductAssignToVendor::where('id', $id)->delete();
    //     if ($result) {
    //         return response()->json(['success' => 'Assignment deleted successfully!']);
    //     } else {
    //         return response()->json(['error' => 'Error deleting assignment.'], 404);
    //     }
    // }

    public function destroy($id, $productId, $storeManagerId, $storeId)
    {
        // return $id;
        ProductAssignToVendor::destroy($id);
        return redirect()->route('products-assignVendor', ['productId' => $productId, 'storeManagerId' => $storeManagerId, 'storeId' => $storeId])->with(['status' => true, 'message' => 'Product Deleted Successfully']);
    }





    public function getVendorDepartments($vendorId, $productId)
    {
        try {
            $departments_id = AssignVendorToDepartment::where('vendor_id', $vendorId)->pluck('department_id');

            $departments = Department::whereIn('id', function ($query) use ($departments_id, $productId) {
                $query->select('department_id')
                    ->from('product_assign_to_departments')
                    ->whereIn('department_id', $departments_id)
                    ->where('product_id', $productId);
            })->get();
            return response()->json([
                'data' => $departments,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No StoreId Found ' . $e->getMessage(),
                'status' => 'Error'
            ], 500);
        }
    }

    // public function getDepartmentsVendors(Request $request, $departmentId, $storeManagerId, $storeId)
    // {
    //     // return $storeManagerId;
    //     // return [$departmentId,$storeManagerId,$storeId];

    //     try {

    //         $data = AssignVendorToDepartment::where('department_id', $departmentId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->get();

    //         return $data;

    //         return response()->json([
    //             'data' => $data,
    //             'status' => 'Success'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error: ' . $e->getMessage(),
    //             'status' => 'Error'
    //         ], 500);
    //     }
    // }


    // public function getVendorStoresEdit(Request $request, $id)
    // {
    //     try {
    //         $data = Store::where("storeManger_id", $id)->get();
    //         return response()->json([
    //             'data' => $data,
    //             'status' => 'Success'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'No StoreId Found ' . $e->getMessage(),
    //             'status' => 'Error'
    //         ], 500);
    //     }
    // }

    // public function getVendorDepartmentsEdit(Request $request, $id)
    // {
    //     try {
    //         $data = Department::where("store_id", $id)->get();
    //         return response()->json([
    //             'data' => $data,
    //             'status' => 'Success'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'No StoreId Found ' . $e->getMessage(),
    //             'status' => 'Error'
    //         ], 500);
    //     }
    // }

    // public function getDepartmentsProductEdit(Request $request, $id)
    // {
    //     try {
    //         // return $id;
    //         $data = ProductAssignToDepartment::where("department_id", $id)->get();
    //         // return $data;
    //         $products = [];

    //         // Extract product IDs from $data
    //         $productIds = collect($data)->pluck('product_id')->all();

    //         // Retrieve all products in a single query
    //         $products = Product::whereIn('id', $productIds)->get();
    //         // return $products;
    //         return response()->json([
    //             'data' => $products,
    //             'status' => 'Success'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'No DepartmentId Found ' . $e->getMessage(),
    //             'status' => 'Error'
    //         ], 500);
    //     }
    // }
}

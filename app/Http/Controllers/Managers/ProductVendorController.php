<?php

namespace App\Http\Controllers\Managers;

use Exception;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToVendor;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;
use Illuminate\Support\Facades\Validator;
use App\Models\StoreManagerStoreDepartment;

use App\Http\Controllers\Admin\AssignVendorToDepartment as AdminAssignVendorToDepartment;

class ProductVendorController extends Controller
{
    // public function index($storeManagerId, $storeId, $id)
    // {
    //     // return $storeId;

    //     $assignedProducts = ProductAssignToVendor::where('store_manager_id', $storeManagerId)->where('store_id', $storeId)->where('product_id', $id)->orderBy('id', 'DESC')->get();
    //     return $assignedProducts;
    //  return view('managers.productVendor.index', compact('storeManagerId', 'storeId', 'id','assignedProducts'));
    // }

    public function index($storeManagerId, $storeId, $id)
    {
        $assignedProducts = ProductAssignToVendor::with(['vendor', 'departments'])
            ->where('store_manager_id', $storeManagerId)
            ->where('store_id', $storeId)
            ->where('product_id', $id)->orderBy('vendor_id', 'DESC')
            ->get();
            $product = Product::where('store_id', $storeId)->where('id', $id)->first();

            // return $assignedProducts;
        return view('managers.productVendor.index', compact('storeManagerId', 'storeId', 'id', 'assignedProducts','product'));
    }

    public function create($id)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $product = Product::find($id);
        $productName = $product->product_name;
        // return $productName;

        // $vendorAssignments = AssignVendorToDepartment::where('store_manager_id', $authId)
        //     ->where('store_id', $storeId)
        //     ->get();
        // $vendors = [];


        // foreach ($vendorAssignments as $vendorAssignment) {
        //     $vendors[] = $vendorAssignment->vendors;
        // }
        // // return $vendors;
        // $vendors = collect($vendors)->unique('id');
        // $vendors = $vendors->flatten();

        // Get vendor assignments based on the authenticated store manager and specific store
        // $vendorAssignments = AssignVendorToDepartment::where('store_manager_id', $authId)
        //     ->where('store_id', $storeId)
        //     ->get();

        // // Extract department IDs from the vendor assignments
        // $departmentIds = $vendorAssignments->pluck('department_id')->unique();

        // Get all departments with assigned products, for those department IDs
        $departmentsWithProducts = ProductAssignToDepartment::where('product_id', $id)
            ->get();

        $departmentIds = $departmentsWithProducts->pluck('department_id')->unique();
        $vendorAssignments = AssignVendorToDepartment::with('vendors')->where('store_manager_id', $authId)
            ->where('store_id', $storeId)->whereIn('department_id', $departmentIds)
            ->get()->unique('vendor_id');

            $product = Product::where('store_id', $storeId)->where('id', $id)->first();


        // $uniqueVendors = $vendorAssignments->pluck('vendors')->unique('id');

        // return $uniqueVendors;

        // foreach ($departmentsWithProducts as $departmentWithProduct) {
        //     $department_id = $departmentWithProduct->department_id
        //     $vendor = AssignVendorToDepartment::where('department_id',)
        // }

        return view('managers.productVendor.create', compact('productName', 'id', 'authId', 'storeId', 'vendorAssignments','product'));
    }
    // simple
    // public function store(Request $request, $id)
    // {
    //     // return $request;
    //     $request->validate([
    //         'product_id' => 'required',
    //         'vendor_id' => 'required|array',
    //         'vendor_id.*' => 'required',
    //         'department.*' => 'required',
    //         'department' => 'required|array',
    //         'price.*' => 'required',
    //         'price' => 'required|array'
    //     ]);
    //     //  return $request;
    //     $storeManagerId = $request->input('store_manager_id');
    //     $storeId = $request->input('store_id');
    //     $vendors = $request->input('vendor_id');
    //     $productId = $request->input('product_id');
    //     $departments = $request->input('department');
    //     $prices = $request->input('price');
    //     foreach ($departments as $index => $departmentId) {
    //         $vendorIndex = $index % count($vendors); // Cycle through vendors if fewer than departments
    //         $priceIndex = $index % count($prices); // Cycle through prices if fewer than departments

    //         ProductAssignToVendor::firstOrCreate([
    //             'store_manager_id' => $request->input('store_manager_id'),
    //             'store_id' => $request->input('store_id'),
    //             'vendor_id' => $vendors[$vendorIndex],
    //             'product_id' => $request->input('product_id'),
    //             'department_id' => $departmentId,
    //             'product_price' => $prices[$priceIndex],
    //         ]);
    //     }
    //     // Move the return statement outside of the foreach loop.
    //     return redirect()->route('manager.productVendors', [
    //         'storeManagerId' => $storeManagerId, 'storeId' => $storeId, 'id' => $productId
    //     ])->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    // }

    // public function store(Request $request, $id)
    // {
    //     // return $request;
    //     // Validate the request data
    //     $request->validate([
    //         'product_id' => 'required',
    //         'vendor_id' => 'required|array',
    //         'vendor_id.*' => 'required',
    //         'department.*' => 'required',
    //         'department' => 'required|array',
    //         'price.*' => 'required',
    //         'price' => 'required|array'
    //     ]);

    //     // Extract request input
    //     $storeManagerId = $request->input('store_manager_id');
    //     $storeId = $request->input('store_id');
    //     $vendors = $request->input('vendor_id');
    //     $productId = $request->input('product_id');
    //     $departments = $request->input('department');
    //     $prices = $request->input('price');

    //     foreach ($departments as $index => $departmentId) {
    //         $vendorIndex = $index % count($vendors); // Cycle through vendors if fewer than departments
    //         $priceIndex = $index % count($prices); // Cycle through prices if fewer than departments

    //         // Use updateOrCreate to update the price if vendor and department are the same
    //         ProductAssignToVendor::updateOrCreate([
    //             // Conditions to find the record
    //             'store_manager_id' => $storeManagerId,
    //             'store_id' => $storeId,
    //             'vendor_id' => $vendors[$vendorIndex],
    //             'product_id' => $productId,
    //             'department_id' => $departmentId,
    //         ], [
    //             // Values to update or create with
    //             'product_price' => $prices[$priceIndex],
    //         ]);
    //     }

    //     // Redirect with success message
    //     return redirect()->route('manager.productVendors', [
    //         'storeManagerId' => $storeManagerId, 'storeId' => $storeId, 'id' => $productId
    //     ])->with(['status' => true, 'message' => 'Department Assigned Successfully']);
    // }

    public function store(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'wholesaler_id' => 'required|array',
            'wholesaler_id.*' => 'required',
            'department' => 'required|array',
            'department.*' => 'required',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'assignments' => 'array', // Validate if 'assignments' is an array
            'assignments.*.wholesaler_id' => 'required',
            'assignments.*.department.*' => 'required',
            'assignments.*.price' => 'required|numeric|min:0',
        ]);

        $productId = $request->input('product_id');
        $storeManagerId = $request->input('store_manager_id');
        $storeId = $request->input('store_id');

        // Retrieve 'store_manager_id' and 'store_id' once
        $assignments = collect($request->assignments ?? []);

        // Process the initial set of vendor, department, and price
        $vendors = is_array($request->wholesaler_id) ? $request->wholesaler_id : [];
        $departments = is_array($request->department) ? $request->department : [];
        $prices = is_array($request->price) ? $request->price : [];

        foreach ($vendors as $index => $vendorId) {
            $price = $prices[$index] ?? null; // Use null or any default if no price is set
            foreach ($departments as $departmentId) {
                ProductAssignToVendor::updateOrCreate([
                    'store_manager_id' => $storeManagerId,
                    'store_id' => $storeId,
                    'vendor_id' => $vendorId,
                    'product_id' => $productId,
                    'department_id' => $departmentId,
                ], [
                    'product_price' => $price,
                ]);
            }
        }

        // Process additional assignments
        $assignments->each(function ($assignment) use ($storeManagerId, $storeId, $productId) {
            $vendorId = $assignment['vendor_id'];
            $price = $assignment['price'];

            collect($assignment['department'])->each(function ($departmentId) use ($storeManagerId, $storeId, $vendorId, $productId, $price) {
                ProductAssignToVendor::updateOrCreate([
                    'store_manager_id' => $storeManagerId,
                    'store_id' => $storeId,
                    'vendor_id' => $vendorId,
                    'product_id' => $productId,
                    'department_id' => $departmentId,
                ], [
                    'product_price' => $price,
                ]);
            });
        });

        return redirect()->route('manager.productVendors', ['storeManagerId' => $storeManagerId, 'storeId' => $storeId, 'id' => $productId])
            ->with(['status' => true, 'message' => 'Wholesaler Assigned Successfully']);
    }

    public function edit($id, $vendorId, $productId)
    {
        // return $id;
        $assignedProduct = ProductAssignToVendor::find($id);
        // $assignedProduct->product_price = $assignedProduct->product_price ?? 0;


        // return $assignedProduct;
        //    return $assignedProduct->department_id;


        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $departments_id = AssignVendorToDepartment::where('vendor_id', $vendorId)->pluck('department_id');

        $departments = Department::whereIn('id', function ($query) use ($departments_id, $productId) {
            $query->select('department_id')
                ->from('product_assign_to_departments')
                ->whereIn('department_id', $departments_id)
                ->where('product_id', $productId);
        })->get();

        // return $departments;
        $vendorName = Vendor::where('id', $vendorId)->value('vendor_name');
        $id = $assignedProduct->id;

        $product = Product::where('store_id', $storeId)->where('id', $productId)->first();

        //    return $id;
        // return $vendorName;
        return view('managers.productVendor.edit', compact('authId', 'storeId', 'vendorId', 'productId', 'vendorName', 'assignedProduct', 'departments', 'id','product'));
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

        return redirect()->route('manager.productVendors', [
            'storeManagerId' => $storeManagerId, 'storeId' => $storeId, 'id' => $productId
        ])->with(['status' => true, 'message' => 'Assigned Wholesaler Updated Successfully']);
    }



    public function destory($id, $productId)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        // return $id;
        ProductAssignToVendor::destroy($id);
        return redirect()->route('manager.productVendors', ['storeManagerId' => $authId, 'storeId' => $storeId, 'id' => $productId])->with(['status' => true, 'message' => 'Assigned Wholesaler Deleted Successfully']);
    }




    public function getStoreManagerDepartments($vendorId, $productId)
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


}

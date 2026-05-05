<?php

namespace App\Http\Controllers\Managers;

use Exception;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Department;
use App\Jobs\BulkUploadJob;
use App\Models\BulkUpload;
use App\Models\AssignVendor;
use Illuminate\Http\Request;
use App\Models\ProductAssign;
use App\Models\ProductsPhoto;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToVendor;
use Illuminate\Database\QueryException;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;
use Illuminate\Support\Facades\Validator;
use App\Models\StoreManagerStoreDepartment;



class ManagerProduct extends Controller
{
    // Just loads the page (no data)
    public function index()
    {
        return view('managers.product.index');
    }

    // Fetch products for AJAX
    public function getProducts(Request $request)
    {
        $authId = auth()->guard('web')->id();

        // Get store assigned to manager
        $Store = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $Store->store_id;

        $query = Product::where('store_id', $storeId)
            ->with([
                'storeManager:id,first_name,last_name',
                'store:id,store_name'
            ]);

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('upc_ipc', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'pagination' => $products->links('pagination::bootstrap-4')->render(),
        ]);
    }

    public function create()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        return view('managers.product.create', compact('authId', 'StoreId'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'upc_ipc' => [
                'required',
                Rule::unique('products', 'upc_ipc')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->product_id, 'product_id'),
            ],
            'price' => 'required',
            'store_manager_id' => 'required',
            'store_id' => 'required',

        ]);

        $product = Product::create([
            'store_manager_id' => $request->store_manager_id,
            'store_id' => $request->store_id,
            'product_name' => $request->product_name,
            'upc_ipc' => $request->upc_ipc,
            'price' => $request->price ?? 0, // Defaults to 0 if price is not provided
        ]);

        return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Product Added Successfully']);
    }


    public function edit($id)
    {
        // return $id;
        $product = Product::find($id);
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        // return $product;
        return view('managers.product.edit', compact('product', 'storeId'));
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $product = Product::find($id);
        // return $request;
        $request->validate([
            'product_name' => 'required',
            'upc_ipc' => [
                'required',
                Rule::unique('products', 'upc_ipc')
                    ->where('store_id', $request->store_id)
                    ->ignore($product->id),
            ],
            'price' => 'required'
        ]);
        // return $request;
        $product->update([

            'product_name' => $request->product_name,
            'upc_ipc' => $request->upc_ipc,
            'price' => $request->price,
        ]);
        return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Product Updated Successfully']);
    }

    public function delete($id)
    {
        // return $id;
        Product::destroy($id);
        return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Product Deleted Successfully']);
    }


    public function uploadForm()
    {
        return view('managers.product.bulkUpload');
    }

    // public function bulkUpload(Request $request)
    // {
    //     $authId = Auth::guard('web')->id();

    //     $storeManagerStoreDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
    //     $storeId = $storeManagerStoreDepartment->store_id;

    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|mimes:csv,txt',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'File Type Must Be CSV.']);
    //     }

    //     $file = $request->file('file');
    //     if (!$file) {
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'File Upload Failed ']);
    //     }

    //     $path = $file->getRealPath();
    //     $data = array_map('str_getcsv', file($path));

    //     // Check for empty data
    //     if (empty($data) || !isset($data[0])) {
    //         throw new Exception('No data found in the file.');
    //     }

    //     $header = $data[0];
    //     unset($data[0]);

    //     foreach ($data as $row) {
    //         // Check if the number of elements in the row matches the header
    //         if (count($row) !== count($header)) {
    //             // Handle the error: skip the row, log the error, or return an error response
    //             Log::error('Row does not match header length: ' . implode(',', $row));
    //             continue; // Skip this row
    //         }

    //         $productData = array_combine($header, $row);
    //         // return $productData;
    //         $productData['store_id'] = $storeId;
    //         $productData['store_manager_id'] = $authId;

    //         // $existingProduct = Product::where('upc_ipc', $productData['upc_ipc'])
    //         //     ->where('store_id', $storeId)
    //         //     ->first();

    //         // if ($existingProduct) {
    //         //     // Delete the existing product
    //         //     $existingProduct->delete();
    //         //     Log::info('Duplicate upc_ipc found and deleted: ' . $productData['upc_ipc']);
    //         // }

    //         // $productValidator = Validator::make($productData, [
    //         //     'product_name' => 'required',
    //         //     'upc_ipc' => 'required',
    //         //     'tax_status' => 'required',

    //         // ]);

    //         Product::create($productData);
    //         // return $productData;
    //     }

    //     return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Product Added Successfully']);
    // }

    // public function bulkUpload(Request $request)
    // {
    //     $authId = Auth::guard('web')->id();

    //     $storeManagerStoreDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
    //     $storeId = $storeManagerStoreDepartment->store_id;

    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|mimes:csv,txt',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'File Type Must Be CSV.']);
    //     }

    //     $file = $request->file('file');
    //     if (!$file) {
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'File Upload Failed ']);
    //     }

    //     $path = $file->getRealPath();
    //     $data = array_map('str_getcsv', file($path));

    //     // Check for empty data
    //     if (empty($data) || !isset($data[0])) {
    //         Log::error('No data found in the file.');
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'No data found in the file.']);
    //     }

    //     $header = $data[0];
    //     unset($data[0]);

    //     DB::beginTransaction();

    //     try {
    //         foreach ($data as $row) {
    //             // Check if the number of elements in the row matches the header
    //             if (count($row) !== count($header)) {
    //                 Log::error('Row does not match header length: ' . implode(',', $row));
    //                 continue; // Skip this row
    //             }

    //             $productData = array_combine($header, $row);
    //             Log::info('Processing product data: ' . json_encode($productData));

    //             $productData['store_id'] = $storeId;
    //             $productData['store_manager_id'] = $authId;

    //             // Create or find the product
    //             $product = Product::firstOrCreate(
    //                 [
    //                     'upc' => $productData['upc'],
    //                     'store_id' => $storeId,
    //                 ],
    //                 [
    //                     'retail_price' => $productData['retail_price'],
    //                     'name' => $productData['product_name'],

    //                 ]
    //             );

    //             $department = Department::firstOrCreate(
    //                 [
    //                     'department' => $productData['Department'],
    //                     'tax_status' => $productData['tax_status'],
    //                 ]
    //             );

    //             // Process vendors
    //             $vendorNames = explode(',', $productData['vendor_name']);
    //             $vendorPrices = explode(',', $productData['Vendor_price']);

    //             foreach ($vendorNames as $index => $vendorName) {
    //                 $vendorName = trim($vendorName);
    //                 $vendorPrice = isset($vendorPrices[$index]) ? trim($vendorPrices[$index]) : 0;

    //                 // Create or find the vendor
    //                 $vendor = Vendor::firstOrCreate(['name' => $vendorName]);

    //                 // Attach the vendor to the product with the price
    //                 $product->vendors()->syncWithoutDetaching([$vendor->id => ['vendor_price' => $vendorPrice]]);
    //             }
    //         }

    //         DB::commit();
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Products Added Successfully']);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error during bulk upload: ' . $e->getMessage());
    //         return redirect()->route('manager.storeManagerProducts')->with(['status' => false, 'message' => 'There was an error uploading the file.']);
    //     }
    // }


    // #working code of bulk
    // public function bulkUpload(Request $request)
    // {
    //     set_time_limit(3600); // Set time limit to 10 minutes
    //     $authId = Auth::guard('web')->id();
    //     $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
    //     $storeId = $StoreId->store_id;

    //     // Validate the request
    //     $request->validate([
    //         'file' => 'required|mimes:csv'
    //     ]);
    //     // return $request;
    //     // return $request;

    //     // Read the CSV file
    //     $file = $request->file('file');
    //     $csvData = file_get_contents($file);
    //     $rows = array_map('str_getcsv', explode("\n", $csvData));

    //     // Remove empty rows
    //     $rows = array_filter($rows);

    //     // return $rows;

    //     // Remove header row if present
    //     $header = array_shift($rows);

    //     // Insert data into the database
    //     foreach ($rows as $row) {
    //         // Handle missing columns gracefully
    //         $upc_ipc = isset($row[0]) ? trim($row[0]) : null;
    //         $department_names = isset($row[1]) ? explode(',', $row[1]) : [];
    //         $price = isset($row[2]) ? trim($row[2]) : null;
    //         $product_name = isset($row[3]) ? trim($row[3]) : null;
    //         $tax_statuses = isset($row[4]) ? explode(',', $row[4]) : [];
    //         $vendor_names = isset($row[5]) ? explode(',', $row[5]) : [];
    //         $product_prices = isset($row[6]) ? explode(',', $row[6]) : [];
    //         // return $product_name;
    //         // Insert each vendor name with its corresponding data
    //         foreach ($vendor_names as $index => $vendor_name) {
    //             $vendor_name = trim($vendor_name); // Remove leading and trailing spaces
    //             // Ignore vendor name if empty after trimming
    //             if (empty($vendor_name)) {
    //                 continue;
    //             }

    //             // Check if vendor already exists or create a new one
    //             try {
    //                 $vendor = Vendor::firstOrCreate(
    //                     ['vendor_name' => $vendor_name, 'general_discount' => '0'],
    //                     ['vendor_name' => $vendor_name] // Insert only if the vendor doesn't exist
    //                 );
    //             } catch (\Illuminate\Database\QueryException $e) {
    //                 // Catch the query exception and show the error message
    //                 dd($e->getMessage());
    //             }
    //             // return $vendor_name;

    //             // Check if vendor was successfully created or retrieved
    //             if ($vendor) {
    //                 // Assign vendor to manager if not already assigned
    //                 $assignVendor = AssignVendor::firstOrCreate([
    //                     'store_manager_id' => $authId,
    //                     'store_id' => $storeId,
    //                     'vendor_id' => $vendor->id,
    //                 ]);
    //                 // return $vendor;
    //                 // Insert department data
    //                 foreach ($department_names as $dept_name_index => $dept_name) {
    //                     $department_name = trim($dept_name);
    //                     $tax_status_value = (isset($tax_statuses[$dept_name_index]) && $tax_statuses[$dept_name_index] === "taxable") ? 1 : 0;

    //                     // Create department if it doesn't exist
    //                     $department = Department::firstOrCreate([
    //                         'store_manager_id' => $authId,
    //                         'store_id' => $storeId,
    //                         'department_name' => $department_name,
    //                         'tax_status' => $tax_status_value,
    //                     ]);

    //                     // Assign vendor to department
    //                     AssignVendorToDepartment::firstOrCreate([
    //                         'store_manager_id' => $authId,
    //                         'store_id' => $storeId,
    //                         'vendor_id' => $vendor->id,
    //                         'department_id' => $department->id,
    //                         'assignVendor_id' => $assignVendor->id,

    //                     ]);

    //                     // Insert product data if all required fields are present
    //                     if (!empty($product_name) && !empty($upc_ipc) && !empty($price)) {
    //                         $product = Product::updateOrCreate(
    //                             [
    //                                 'upc_ipc' => $upc_ipc, // Match based on UPC/IPC
    //                                 'store_manager_id' => $authId,
    //                                 'store_id' => $storeId,
    //                             ],
    //                             [
    //                                 'product_name' => $product_name,
    //                                 'price' => $price,
    //                             ]
    //                         );
    //                         // return $product;

    //                         if ($product) {

    //                             ProductAssign::updateOrCreate([
    //                                 'store_manager_id' => $authId,
    //                                 'store_id' => $storeId,
    //                                 'product_id' => $product->id,
    //                             ]);

    //                             ProductAssignToDepartment::updateOrCreate([
    //                                 'store_manager_id' => $authId,
    //                                 'store_id' => $storeId,
    //                                 'department_id' => $department->id,
    //                                 'product_id' => $product->id,
    //                             ]);
    //                             // Save product assignment to product_assign_to_vendors table
    //                             ProductAssignToVendor::updateOrCreate(
    //                                 [
    //                                     'store_manager_id' => $authId,
    //                                     'store_id' => $storeId,
    //                                     'department_id' => $department->id,
    //                                     'vendor_id' => $vendor->id,
    //                                     'product_id' => $product->id,
    //                                 ],
    //                                 [
    //                                     'product_price' => isset($product_prices[$index]) ? $product_prices[$index] : null,
    //                                 ]
    //                             );

    //                             // return $product->id;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Products Added Successfully']);
    // }



    // public function bulkUpload(Request $request)
    // {
    //     ini_set('max_execution_time', 1800); // Set time limit to 10 minutes
    //     ini_set('memory_limit', '512M'); // Increase memory limit if needed
    //     $authId = Auth::guard('web')->id();
    //     $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
    //     $storeId = $StoreId ? $StoreId->store_id : null;

    //     if (!$storeId) {
    //         return redirect()->back()->withErrors(['message' => 'Store ID not found for the store manager.']);
    //     }

    //     // Validate the request
    //     // $request->validate([
    //     //     'file' => 'required|mimes:csv'
    //     // ]);

    //     // Read the CSV file
    //     $file = $request->file('file');
    //     $csvData = file_get_contents($file);
    //     $rows = array_map('str_getcsv', explode("\n", $csvData));
    //     $rows = array_filter($rows); // Remove empty rows
    //     $header = array_shift($rows); // Remove header row if present

    //     $vendors = Vendor::pluck('id', 'vendor_name')->toArray(); // Cache vendor names
    //     $departments = Department::where('store_manager_id', $authId)->pluck('id', 'department_name')->toArray(); // Cache departments

    //     foreach ($rows as $row) {
    //         $upc_ipc = isset($row[0]) ? trim($row[0]) : null;
    //         $department_names = isset($row[1]) ? explode(',', $row[1]) : [];
    //         $price = isset($row[2]) ? trim($row[2]) : null;
    //         $product_name = isset($row[3]) ? trim($row[3]) : null;
    //         $tax_statuses = isset($row[4]) ? explode(',', $row[4]) : [];
    //         $vendor_names = isset($row[5]) ? explode(',', $row[5]) : [];
    //         $product_prices = isset($row[6]) ? explode(',', $row[6]) : [];

    //         foreach ($vendor_names as $index => $vendor_name) {
    //             $vendor_name = trim($vendor_name);
    //             if (empty($vendor_name)) continue;

    //             // Retrieve or create vendor
    //             $vendor = Vendor::firstOrCreate(['vendor_name' => $vendor_name, 'general_discount' => '0']);
    //             if (!$vendor) continue;

    //             // Assign vendor to manager
    //             $assignVendor = AssignVendor::firstOrCreate([
    //                 'store_manager_id' => $authId,
    //                 'store_id' => $storeId,
    //                 'vendor_id' => $vendor->id,
    //             ]);

    //             foreach ($department_names as $dept_name_index => $dept_name) {
    //                 $department_name = trim($dept_name);
    //                 $tax_status_value = (isset($tax_statuses[$dept_name_index]) && $tax_statuses[$dept_name_index] === "taxable") ? 1 : 0;

    //                 // Retrieve or create department
    //                 $department = Department::firstOrCreate([
    //                     'store_manager_id' => $authId,
    //                     'store_id' => $storeId,
    //                     'department_name' => $department_name,
    //                     'tax_status' => $tax_status_value,
    //                 ]);
    //                 if (!$department) continue;

    //                 // Assign vendor to department
    //                 AssignVendorToDepartment::firstOrCreate([
    //                     'store_manager_id' => $authId,
    //                     'store_id' => $storeId,
    //                     'vendor_id' => $vendor->id,
    //                     'department_id' => $department->id,
    //                     'assignVendor_id' => $assignVendor->id,
    //                 ]);

    //                 // Insert product data if all required fields are present
    //                 if (!empty($product_name) && !empty($upc_ipc) && !empty($price)) {
    //                     $product = Product::updateOrCreate(
    //                         [
    //                             'upc_ipc' => $upc_ipc,
    //                             'store_manager_id' => $authId,
    //                             'store_id' => $storeId,
    //                         ],
    //                         [
    //                             'product_name' => $product_name,
    //                             'price' => $price,
    //                         ]
    //                     );

    //                     if ($product) {
    //                         ProductAssign::updateOrCreate([
    //                             'store_manager_id' => $authId,
    //                             'store_id' => $storeId,
    //                             'product_id' => $product->id,
    //                         ]);

    //                         ProductAssignToDepartment::updateOrCreate([
    //                             'store_manager_id' => $authId,
    //                             'store_id' => $storeId,
    //                             'department_id' => $department->id,
    //                             'product_id' => $product->id,
    //                         ]);

    //                         ProductAssignToVendor::updateOrCreate(
    //                             [
    //                                 'store_manager_id' => $authId,
    //                                 'store_id' => $storeId,
    //                                 'department_id' => $department->id,
    //                                 'vendor_id' => $vendor->id,
    //                                 'product_id' => $product->id,
    //                             ],
    //                             [
    //                                 'product_price' => $product_prices[$index] ?? null,
    //                             ]
    //                         );
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return redirect()->route('manager.storeManagerProducts')->with(['status' => true, 'message' => 'Products Added/Updated Successfully']);
    // }


    // public function bulkUpload(Request $request)
    // {
    //     try {
    //         // Get the store manager's ID and the associated store ID
    //         $authId = Auth::guard('web')->id();
    //         $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
    //         $storeId = $StoreId->store_id;

    //         // Validate the file (optional, uncomment if needed)
    //         // $request->validate([
    //         //     'file' => 'required|mimes:csv'
    //         // ]);

    //         // Check if the file is present
    //         if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid file uploaded.',
    //             ], 400);
    //         }

    //         // Get the file and read its contents
    //         $file = $request->file('file');
    //         $csvData = file_get_contents($file);
    //         $rows = array_map('str_getcsv', explode("\n", $csvData));
    //         $rows = array_filter($rows);

    //         // Remove the header row
    //         array_shift($rows);

    //         // Chunk the rows to avoid too many jobs in the queue at once
    //         $chunkSize = 500; // Process in chunks of 500 rows
    //         $chunks = array_chunk($rows, $chunkSize);

    //         // Dispatch the bulk upload job for each chunk
    //         foreach ($chunks as $chunk) {
    //             BulkUploadJob::dispatch($chunk, $storeId, $authId);
    //         }

    //         return response()->json([
    //             'success' => 'success',
    //             'message' => 'Products upload is being processed.',
    //         ]);

    //     } catch (\Exception $e) {
    //         // Catch any exceptions and return an error response
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred during the upload process: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function bulkUpload(Request $request)
    {
        try {

            $authId = Auth::guard('web')->id();

            $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
            $storeId = $StoreId->store_id;

            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid file uploaded.',
                ], 400);
            }

            $file = $request->file('file');
            $csvData = file_get_contents($file);
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            $rows = array_filter($rows);

            array_shift($rows);

            // ✅ CREATE TRACKING ENTRY
            $upload = BulkUpload::create([
                'store_manager_id' => $authId,
                'store_id' => $storeId,
                'total_records' => count($rows),
                'processed_records' => 0,
                'failed_records' => 0,
                'status' => 'processing'
            ]);

            $chunkSize = 500;
            $chunks = array_chunk($rows, $chunkSize);

            foreach ($chunks as $chunk) {
                BulkUploadJob::dispatch($chunk, $storeId, $authId, $upload->id);
            }

            return response()->json([
                'success' => true,
                'upload_id' => $upload->id,
                'message' => 'Products upload started.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function bulkUploadProgress($id)
    {
        $upload = BulkUpload::find($id);

        return response()->json([
            'processed' => $upload->processed_records,
            'total' => $upload->total_records,
            'failed' => $upload->failed_records,
            'status' => $upload->status
        ]);
    }
    

}

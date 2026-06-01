<?php

namespace App\Http\Controllers\Api;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\OrderItem;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\HotSaleProduct;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAssignToVendor;

class WholesalerController extends Controller
{
    // public function wholesalers($storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch vendors assigned to the given store manager ID and store ID
    //         $vendors = ProductAssignToVendor::select('store_manager_id', 'vendor_id')
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->with('vendor')
    //             ->get();

    //         if ($vendors->isEmpty()) {
    //             return response()->json(['message' => 'No vendors found for this store manager ID and store ID']);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'wholesalers get successfully',
    //             'Vendors' => $vendors
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching vendors', 'details' => $e->getMessage()]);
    //     }
    // }

    public function wholesalers($storeManagerId, $storeId)
    {
        try {
            // Fetch unique vendors assigned to the given store manager ID and store ID
            $vendors = ProductAssignToVendor::select('store_manager_id', 'vendor_id')
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->with('vendor')
                ->distinct('vendor_id') // Ensure unique vendors by vendor_id
                ->get(); 
  
            if ($vendors->isEmpty()) {
                return response()->json(['message' => 'No vendors found for this store manager ID and store ID']);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Wholesalers fetched successfully',
                'Vendors' => $vendors
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching vendors', 'details' => $e->getMessage()]);
        }
    }






    // public function departments($vendorId, $storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch distinct department IDs assigned to the given vendor ID, store manager ID, and store ID
    //         $distinctDepartments = ProductAssignToVendor::select('department_id')
    //             ->where('vendor_id', $vendorId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->distinct()
    //             ->get();

    //         if ($distinctDepartments->isEmpty()) {
    //             return response()->json(['message' => 'No departments found for this vendor ID, store manager ID, and store ID']);
    //         }

    //         // Get the department details
    //         $departmentIds = $distinctDepartments->pluck('department_id');
    //         $departments = Department::whereIn('id', $departmentIds)->get();

    //         return response()->json([
    //             'status'=>'success',
    //             'message'=>'departments get successfully',
    //             'storeDepartments' => $departments
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching departments', 'details' => $e->getMessage()]);
    //     }
    // }

    public function departments($vendorId, $storeManagerId, $storeId)
    {
        try {
            // Fetch distinct department IDs assigned to the given vendor ID, store manager ID, and store ID
            $distinctDepartments = ProductAssignToVendor::select('department_id')
                ->where('vendor_id', $vendorId)
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->distinct()
                ->get();

            if ($distinctDepartments->isEmpty()) {
                return response()->json(['message' => 'No departments found for this vendor ID, store manager ID, and store ID']);
            }

            // Get the department details
            $departmentIds = $distinctDepartments->pluck('department_id');
            $departments = Department::whereIn('id', $departmentIds)->get();

            // Map the departments to include tax status and vendor_id
            $departmentsWithTaxStatus = $departments->map(function ($department) use ($vendorId) {
                return [
                    'id' => $department->id,
                    'store_manager_id' => $department->store_manager_id,
                    'store_id' => $department->store_id,
                    'department_name' => $department->department_name,
                    'tax_status' => $department->tax_status == 1 ? 'taxable' : 'non-taxable',
                    'image' => $department->image,
                    'created_at' => $department->created_at,
                    'updated_at' => $department->updated_at,
                    'vendor_id' => $vendorId, // Add vendor_id to the response
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Departments retrieved successfully',
                'storeDepartments' => $departmentsWithTaxStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching departments', 'details' => $e->getMessage()]);
        }
    }








    // public function products($vendorId, $id, $storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch vendors assigned to the given store manager ID
    //         $products = ProductAssignToVendor::select('department_id', 'product_id', 'vendor_id', 'product_price')
    //             ->where('department_id', $id)
    //             ->where('vendor_id', $vendorId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->with('product.productImage', 'vendor') // Eager load the vendor relationship
    //             ->get();

    //         if ($products->isEmpty()) {
    //             return response()->json(['message' => 'No departments found for this department ID']);
    //         }

    //         // Transform products to include only the vendor's name
    //         $productsWithVendorName = $products->map(function ($product) {
    //             return [
    //                 'department_id' => $product->department_id,
    //                 'product_id' => $product->product_id,
    //                 'vendor_id' => $product->vendor_id,
    //                 'vendor_name' => $product->vendor ? $product->vendor->vendor_name : null,
    //                 'product_price' => $product->product_price,
    //                 'product' => $product->product,
    //             ];
    //         });

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Products fetched successfully',
    //             'vendorproducts' => $productsWithVendorName
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching products', 'details' => $e->getMessage()]);
    //     }
    // }

    //     public function products($vendorId, $id, $storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch vendors assigned to the given store manager ID
    //         $products = ProductAssignToVendor::select('department_id', 'product_id', 'vendor_id', 'product_price')
    //             ->where('department_id', $id)
    //             ->where('vendor_id', $vendorId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->with('product.productImage', 'vendor') // Eager load the vendor relationship
    //             ->get();

    //         if ($products->isEmpty()) {
    //             return response()->json(['message' => 'No products found for this department ID']);
    //         }

    //         // Transform products to include only the vendor's name and check if in wishlist
    //         $productsWithVendorName = $products->map(function ($product) use ($storeManagerId, $storeId, $vendorId) {
    //             $isInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
    //                 ->where('store_id', $storeId)
    //                 ->where('vendor_id', $vendorId)
    //                 ->where('product_id', $product->product_id)
    //                 ->exists();

    //             return [
    //                 'department_id' => $product->department_id,
    //                 'product_id' => $product->product_id,
    //                 'vendor_id' => $product->vendor_id,
    //                 'vendor_name' => $product->vendor ? $product->vendor->vendor_name : null,
    //                 'product_price' => $product->product_price,
    //                 'product' => $product->product,
    //                 'is_in_wishlist' => $isInWishlist
    //             ];
    //         });

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Products fetched successfully',
    //             'vendorproducts' => $productsWithVendorName
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching products', 'details' => $e->getMessage()]);
    //     }
    // }

    public function products($vendorId, $id, $storeManagerId, $storeId)
    {
        try { 
            // Fetch products assigned to the given store manager ID and vendor

            // return Department::where('store_manager_id', $storeManagerId)->where('store_id', $storeId)->first();
            $products = ProductAssignToVendor::select('department_id', 'product_id', 'vendor_id', 'product_price')
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->where('department_id', $id)
                ->where('vendor_id', $vendorId)
                 ->with([
                    'product.productImage',
                    'vendor.salesMen',
                    'vendor.discount'
                ]) // Eager load the vendor relationship
                ->get();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found for this department ID']);
            }

            $quantityThreshold = HotSaleProduct::first();

            if (!$quantityThreshold) {
                return response()->json(['message' => 'Hot sale product threshold not set']);
            }

            // Transform products to include vendor name and check wishlist status
            $productsWithVendorName = $products->map(function ($product) use ($storeManagerId, $storeId, $vendorId, $quantityThreshold) {
                // Check if the product is in the wishlist
                $isInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
                    ->where('store_id', $storeId)
                    ->where('vendor_id', $vendorId)
                    ->where('product_id', $product->product_id)
                    ->exists();

                // Calculate total sold quantity for the product
                $totalSoldQuantity = Orders::where('vendor_id', $vendorId)
                    ->where('status', 'Completed')
                    ->with(['orderItem' => function ($query) use ($product) {
                        $query->where('product_id', $product->product_id);
                    }])
                    ->get()
                    ->sum(function ($order) {
                        return $order->orderItem->sum('quantity');
                    });

                // Check if the product is a hot seller
                $hotSelling = $totalSoldQuantity > $quantityThreshold->quantity;

                return [     
                    'department_id' => $product->department_id,
                    'product_id' => $product->product_id,
                    'vendor_id' => $product->vendor_id,
                    'vendor_name' => $product->vendor ? $product->vendor->vendor_name : null,
                    // 'discount' => $product->vendor ? $product->vendor->general_discount : null,
                    'discount' => optional($product->vendor->discount)->general_discount ?? 0,
                    'product_price' => $product->product_price,
                    'product' => $product->product,
                    'is_in_wishlist' => $isInWishlist,
                    'total_sold_quantity' => $totalSoldQuantity, // Include total sold quantity
                    'hot_selling' => $hotSelling,
                ];     
            });   
                                                               
            return response()->json([
                'status' => 'success',
                'message' => 'Products fetched successfully',
                'vendorproducts' => $productsWithVendorName
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching products', 'details' => $e->getMessage()]);
        }
    }






    // public function getProducts($vendorId, $departmentId, $productId, $storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch products assigned to the given department ID and vendor ID with product and productImage relationships
    //         $products = ProductAssignToVendor::with('product.productImage','vendor')
    //             ->where('department_id', $departmentId)
    //             ->where('vendor_id', $vendorId)
    //             ->where('product_id', $productId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             // ->select('department_id', 'product_id','product_price')
    //             ->get();

    //         // $products = ProductAssignToVendor::with('vendor')->get();

    //         // Check if the products collection is empty
    //         if ($products->isEmpty()) {
    //             return response()->json(['message' => 'No products found for this department and vendor ID']);
    //         }

    //         // Transform the response to include only product name, price, and image


    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'products detail get successfully',
    //             'vendorproducts' => $products
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching products', 'details' => $e->getMessage()]);
    //     }
    // }

    public function getProducts($vendorId, $departmentId, $productId, $storeManagerId, $storeId)
    {
        try {
            // Fetch products assigned to the given department ID and vendor ID with product and productImage relationships
            $products = ProductAssignToVendor::with('product.productImage', 'vendor')
                ->where('department_id', $departmentId)
                ->where('vendor_id', $vendorId)
                ->where('product_id', $productId)
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->select('department_id', 'product_id', 'vendor_id', 'store_id', 'product_price')
                ->get();

            // Check if the products collection is empty
            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found for this department and vendor ID']);
            }

            // Iterate through products and add the 'is_in_wishlist' key based on wishlist existence
            $products->transform(function ($product) use ($storeManagerId) {
                $productInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
                    ->where('product_id', $product->product_id)
                    ->where('vendor_id', $product->vendor_id)
                    ->where('store_id', $product->store_id)
                    ->exists();

                // Add 'is_in_wishlist' to each product
                $product->is_in_wishlist = $productInWishlist ? true : false;

                // Return the product after modification
                return $product;
            });

            // Return transformed products with 'is_in_wishlist'
            return response()->json([
                'status' => 'success',
                'message' => 'Products detail fetched successfully',
                'vendorproducts' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching products', 'details' => $e->getMessage()]);
        }
    }


    // public function getVendorsForProduct($productId, $storeManagerId, $storeId, $excludedVendorId)
    // {
    //     try {

    //         $exist = Vendor::where('id',$excludedVendorId)->first();
    //         if(!$exist)
    //         {
    //             return response()->json(['message' => 'No vendor Exist on this Vendor ID']);
    //         }
    //         // Fetch vendors assigned to the given product ID with product and vendor details
    //         $vendors = ProductAssignToVendor::select('department_id', 'vendor_id', 'product_id', 'product_price')
    //             ->where('product_id', $productId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('vendor_id', '!=', $excludedVendorId) // Exclude the specific vendor ID
    //             ->with(['product' => function ($query) {
    //                 $query->with('productImage'); // Get all product details including images
    //             }, 'vendor' => function ($query) {
    //                 $query->select('id', 'vendor_name');
    //             }])
    //             ->get();

    //         // Check if the vendors collection is empty
    //         if ($vendors->isEmpty()) {
    //             return response()->json(['message' => 'No vendors found for this product ID']);
    //         }

    //         // Transform the response to include vendor details, product details, and department ID
    //         $productsVendor = [];
    //         foreach ($vendors as $assignment) {
    //             $product = $assignment->product;
    //             $productsVendor[] = [
    //                 'department_id' => $assignment->department_id, // Include department_id
    //                 'vendor_id' => $assignment->vendor_id,
    //                 'vendor_name' => $assignment->vendor->vendor_name,
    //                 'product' => [
    //                     'id' => $product->id,
    //                     'product_name' => $product->product_name,
    //                     'upc_ipc' => $product->upc_ipc,
    //                     'price' => $product->price,
    //                     'store_manager_id' => $product->store_manager_id,
    //                     'store_id' => $product->store_id,
    //                     'quantity' => $product->quantity,
    //                     'status' => $product->status,
    //                     'product_images' => $product->productImage->pluck('product_image')->toArray()
    //                 ],
    //                 'price' => $assignment->product_price // Fetch price from ProductAssignToVendor table
    //             ];
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Product vendors fetched successfully',
    //             'productsVendor' => $productsVendor
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching vendors and products', 'details' => $e->getMessage()]);
    //     }
    // }

    public function getVendorsForProduct($productId, $storeManagerId, $storeId, $excludedVendorId)
    {
        try {
            // Check if excluded vendor exists
            $exist = Vendor::where('id', $excludedVendorId)->first();
            if (!$exist) {
                return response()->json(['message' => 'No vendor exists on this Vendor ID']);
            }

            // Fetch vendors assigned to the given product ID with product and vendor details
            $vendors = ProductAssignToVendor::select('department_id', 'vendor_id', 'product_id', 'product_price')
                ->where('product_id', $productId)
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->where('vendor_id', '!=', $excludedVendorId) // Exclude the specific vendor ID
                ->with(['product' => function ($query) {
                    $query->with('productImage'); // Get all product details including images
                }, 'vendor' => function ($query) {
                    $query->select('id', 'vendor_name', 'general_discount');
                }])
                ->get();

            // Check if the vendors collection is empty
            if ($vendors->isEmpty()) {
                return response()->json(['message' => 'No vendors found for this product ID']);
            }

            // Transform the response to include vendor details, product details, department ID, and is_in_wishlist
            $productsVendor = [];
            foreach ($vendors as $assignment) {
                $product = $assignment->product;
                // return $assignment;
                // Check if the product is in the wishlist for the given store manager, vendor, and store
                $isInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
                    ->where('product_id', $product->id)
                    ->where('vendor_id', $assignment->vendor_id)
                    ->where('store_id', $storeId)
                    ->exists();

                // Build the productsVendor array with the is_in_wishlist status
                $productsVendor[] = [
                    'department_id' => $assignment->department_id, // Include department_id
                    'vendor_id' => $assignment->vendor_id,
                    'vendor_name' => $assignment->vendor->vendor_name,
                    'general_discount' => $assignment->vendor->general_discount,
                    'product' => [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'upc_ipc' => $product->upc_ipc,
                        'price' => $product->price,
                        'store_manager_id' => $product->store_manager_id,
                        'store_id' => $product->store_id,
                        'quantity' => $product->quantity,
                        'status' => $product->status,
                        'product_images' => $product->productImage->pluck('product_image')->toArray()
                    ],
                    'price' => $assignment->product_price, // Fetch price from ProductAssignToVendor table
                    'is_in_wishlist' => $isInWishlist // Add is_in_wishlist status
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Product vendors fetched successfully',
                'productsVendor' => $productsVendor
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching vendors and products', 'details' => $e->getMessage()]);
        }
    }



    // public function search(Request $request)
    // {
    //     $search = $request->input('search');

    //     try {
    //         // Search in ProductAssignToVendor for matches
    //         $productAssignResults = ProductAssignToVendor::whereHas('vendor', function ($query) use ($search) {
    //             $query->where('vendor_name', 'LIKE', "%$search%");
    //         })->orWhereHas('product', function ($query) use ($search) {
    //             $query->where('product_name', 'LIKE', "%$search%");
    //         })->orWhereHas('departments', function ($query) use ($search) {
    //             $query->where('department_name', 'LIKE', "%$search%");
    //         })->get();

    //         if ($productAssignResults->isEmpty()) {
    //             return response()->json(['message' => 'No results found for the given query']);
    //         }

    //         // return $productAssignResults;

    //         // Retrieve related data
    //         $vendors = $productAssignResults->pluck('vendor')->unique('id');
    //         $products = $productAssignResults->pluck('product')->unique('id');
    //         $departments = $productAssignResults->pluck('departments')->unique('id');

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Search results fetched successfully',
    //             'results' => [
    //                 'vendors' => $vendors,
    //                 'products' => $products,
    //                 'departments' => $departments
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching search results', 'details' => $e->getMessage()], 500);
    //     }
    // }


    // public function search(Request $request)
    // {
    //     $search = $request->query('search');

    //     try {
    //         // Search in ProductAssignToVendor for matches
    //         $productAssignResults = ProductAssignToVendor::whereHas('vendor', function ($query) use ($search) {
    //             $query->where('vendor_name', 'LIKE', "%$search%");
    //         })->orWhereHas('product', function ($query) use ($search) {
    //             $query->where('product_name', 'LIKE', "%$search%");
    //         // })->orWhereHas('departments', function ($query) use ($search) {
    //         //     $query->where('department_name', 'LIKE', "%$search%");
    //         })->get();

    //         if ($productAssignResults->isEmpty()) {
    //             return response()->json(['message' => 'No results found for the given query']);
    //         }

    //         // Prepare the results to include product_price
    //         $results = $productAssignResults->map(function ($item) {
    //             return [
    //                 'product_id' => $item->product_id,
    //                 'vendor_id' => $item->vendor_id,
    //                 'department_id' => $item->department_id,
    //                 'product_price' => $item->product_price,
    //                 'vendor' => $item->vendor, // Include the full vendor object
    //                 'product' => $item->product, // Include the full product object
    //                 'product_images' => $item->product->productImage->pluck('product_image'), // Include the product images
    //                 'department' => $item->departments // Include the full department object
    //             ];
    //         });

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Search results fetched successfully',
    //             'results' => $results
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while fetching search results', 'details' => $e->getMessage()], 500);
    //     }
    // }

    public function search(Request $request)
    {
        // Get the search query
        $search = $request->query('search');

        // Check if search is empty
        if (empty($search)) {
            return response()->json([
                'status' => 'success',
                'message' => 'No search parameter provided.',
                'results' => [] // Return an empty array when no search parameter is provided
            ]);
        }

        try {
            // Fetch the hot sale quantity threshold
            $quantityThreshold = HotSaleProduct::first();

            // Search in ProductAssignToVendor for matches
            $productAssignResults = ProductAssignToVendor::with(['vendor', 'product', 'product.productImage', 'departments'])
                ->whereHas('vendor', function ($query) use ($search) {
                    $query->where('vendor_name', 'LIKE', "%$search%");
                })
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('product_name', 'LIKE', "%$search%");
                })
                ->get();

            // Check if results are empty
            if ($productAssignResults->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No results found for the given query.',
                    'results' => [] // Return an empty array when no results are found
                ]);
            }

            // Get the currently authenticated store manager's ID
            $storeManagerId = auth()->id();
            // return $storeManagerId;

            // Prepare the results and handle wishlist retrieval
            $results = $productAssignResults->map(function ($product) use ($storeManagerId, $quantityThreshold) {
                // Check if the product is already in the wishlist
                $productId = $product->product_id;
                $storeId = $product->store_id;
                $vendorId = $product->vendor_id;
                $storeManagerId =  $product->store_manager_id;
                // return $storeManagerId;

                $isInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
                    ->where('store_id', $storeId)
                    ->where('vendor_id', $vendorId)
                    ->where('product_id', $productId)
                    ->exists();

                // return $isInWishlist;

                // Calculate total sold quantity for the product
                $totalSoldQuantity = Orders::where('vendor_id', $product->vendor_id)
                    ->where('status', 'Completed')
                    ->with(['orderItem' => function ($query) use ($product) {
                        $query->where('product_id', $product->product_id);
                    }])
                    ->get()
                    ->sum(function ($order) {
                        return $order->orderItem->sum('quantity');
                    });

                // Check if the product is hot selling
                $hotSelling = $totalSoldQuantity > $quantityThreshold->quantity;

                return [
                    'department_id' => $product->department_id,
                    'product_id' => $product->product_id,
                    'vendor_id' => $product->vendor_id,
                    'vendor_name' => $product->vendor ? $product->vendor->vendor_name : null,
                    'product_price' => $product->product_price,
                    'product' => $product->product, // Include the product details
                    'is_in_wishlist' => $isInWishlist, // Include wishlist status
                    'total_sold_quantity' => $totalSoldQuantity, // Include total sold quantity
                    'hot_selling' => $hotSelling, // Include hot selling status
                    'product_images' => $product->product->productImage->pluck('product_image'), // Include product images
                    'department' => $product->departments // Include the department details
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Search results fetched successfully',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching search results', 'details' => $e->getMessage()], 500);
        }
    }

    public function productSearch(Request $request)
    {
        // Get the search query and filters
        $search = $request->query('search');
        $storeManagerId = $request->query('store_manager_id');
        $storeId = $request->query('store_id');
        $vendorId = $request->query('vendor_id');
        $departmentId = $request->query('department_id');

        // Check if search is empty
        if (empty($search)) {
            return response()->json([
                'status' => 'success',
                'message' => 'No search parameter provided.',
                'results' => [] // Return an empty array when no search parameter is provided
            ]);
        }

        try {
            // Fetch the hot sale quantity threshold
            $quantityThreshold = HotSaleProduct::first();

            // Search in ProductAssignToVendor for matches with additional filters
            $productAssignResults = ProductAssignToVendor::with(['vendor', 'product', 'product.productImage', 'departments'])
                ->when($vendorId, function ($query) use ($vendorId) {
                    return $query->where('vendor_id', $vendorId);
                })
                ->when($departmentId, function ($query) use ($departmentId) {
                    return $query->where('department_id', $departmentId);
                })
                ->when($storeId, function ($query) use ($storeId) {
                    return $query->where('store_id', $storeId);
                })
                ->where(function($query) use ($search) {
                    // Apply search condition for both vendor and product
                    $query->whereHas('vendor', function ($vendorQuery) use ($search) {
                        $vendorQuery->where('vendor_name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('product_name', 'LIKE', "%$search%");
                    });
                })
                ->get();

            // Check if results are empty
            if ($productAssignResults->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'No results found for the given query.',
                    'results' => [] // Return an empty array when no results are found
                ]);
            }

            // Prepare the results and handle wishlist retrieval
            $results = $productAssignResults->map(function ($product) use ($storeManagerId, $quantityThreshold) {
                // Check if the product is already in the wishlist
                $productId = $product->product_id;
                $storeId = $product->store_id;
                $vendorId = $product->vendor_id;

                $isInWishlist = Wishlist::where('store_manager_id', $storeManagerId)
                    ->where('store_id', $storeId)
                    ->where('vendor_id', $vendorId)
                    ->where('product_id', $productId)
                    ->exists();

                // Calculate total sold quantity for the product
                $totalSoldQuantity = Orders::where('vendor_id', $product->vendor_id)
                    ->where('status', 'Completed')
                    ->with(['orderItem' => function ($query) use ($product) {
                        $query->where('product_id', $product->product_id);
                    }])
                    ->get()
                    ->sum(function ($order) {
                        return $order->orderItem->sum('quantity');
                    });

                // Check if the product is hot selling
                $hotSelling = $totalSoldQuantity > $quantityThreshold->quantity;

                return [
                    'department_id' => $product->department_id,
                    'product_id' => $product->product_id,
                    'vendor_id' => $product->vendor_id,
                    'vendor_name' => $product->vendor ? $product->vendor->vendor_name : null,
                    'product_price' => $product->product_price,
                    'product' => $product->product, // Include the product details
                    'is_in_wishlist' => $isInWishlist, // Include wishlist status
                    'total_sold_quantity' => $totalSoldQuantity, // Include total sold quantity
                    'hot_selling' => $hotSelling, // Include hot selling status
                    'product_images' => $product->product->productImage->pluck('product_image'), // Include product images
                    'department' => $product->departments // Include the department details
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Search results fetched successfully',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching search results', 'details' => $e->getMessage()], 500);
        }
    }

}

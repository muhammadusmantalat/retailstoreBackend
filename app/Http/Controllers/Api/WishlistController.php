<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductAssignToVendor;

class WishlistController extends Controller
{
    //  public function addToWishlist(Request $request)
    // {

    //     // Update or create the wishlist entry
    //     $wishlist = Wishlist::updateOrCreate(
    //         [
    //             'store_manager_id' => $request->input('store_manager_id'),
    //             'store_id' => $request->input('store_id'),
    //             'product_id' => $request->input('product_id'),
    //             'vendor_id' => $request->input('vendor_id')
    //         ],
    //         [
    //             'status' => $request->input('status')
    //         ]
    //     );

    //     // Check if the status is 1 and update the product status
    //     if ($request->input('status') == 1) {
    //         $productId = $request->input('product_id');
    //         $updatedRows = Product::find($productId);
    //         if($updatedRows)
    //         {
    //             $updatedRows->update([
    //                 'status' => 1
    //             ]);
    //         }
    //     }
    //     else if($request->input('status') == 0){
    //         $productId = $request->input('product_id');
    //         $updatedRows = Product::find($productId);
    //         if($updatedRows)
    //         {
    //             $updatedRows->update([
    //                 'status' => 0
    //             ]);
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'Wishlist updated successfully',
    //         'wishlist' => $wishlist
    //     ]);
    // }
// ----------------------------------------------------------------------

    // public function addToWishlist(Request $request)
    // {
    //     // Retrieve input data
    //     $storeManagerId = $request->input('store_manager_id');
    //     $storeId = $request->input('store_id');
    //     $productId = $request->input('product_id');
    //     $vendorId = $request->input('vendor_id');

    //     // Check if the wishlist entry already exists
    //     $wishlist = Wishlist::where([
    //         ['store_manager_id', $storeManagerId],
    //         ['store_id', $storeId],
    //         ['product_id', $productId],
    //         ['vendor_id', $vendorId]
    //     ])->first();

    //     if ($wishlist) {
    //         // Delete the existing wishlist entry
    //         $wishlist->delete();

    //         // Toggle product status using ternary operator
    //         $product = Product::find($productId);
    //         if ($product) {
    //             $product->update([
    //                 'status' => $product->status == 1 ? 0 : 1
    //             ]);
    //         }

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Removed from wishlist. Product status remains unchanged.',
    //         ]);
    //     } else {
    //         // Create a new wishlist entry with status 1
    //         $wishlist = Wishlist::create([
    //             'store_manager_id' => $storeManagerId,
    //             'store_id' => $storeId,
    //             'product_id' => $productId,
    //             'vendor_id' => $vendorId,
    //             'status' => 1
    //         ]);

    //         // Update the product status to 1
    //         $product = Product::find($productId);
    //         if ($product) {
    //             $product->update(['status' => 1]);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Added to wishlist successfully.',
    //             'wishlist' => $wishlist
    //         ]);
    //     }
    // }

    public function addToWishlist(Request $request)
{
    // Retrieve input data
    $storeManagerId = $request->input('store_manager_id');
    $storeId = $request->input('store_id');
    $productId = $request->input('product_id');
    $vendorId = $request->input('vendor_id');

    // return $vendorId;



    // Check if the wishlist entry already exists
    $wishlist = Wishlist::where([
        ['store_manager_id', $storeManagerId],
        ['store_id', $storeId],
        ['product_id', $productId],
        ['vendor_id', $vendorId]
    ])->first();

// return $wishlist;


    if ($wishlist) {
        // Delete the existing wishlist entry
        $wishlist->delete();

        // Update the product status for this specific vendor
        $productVendor = ProductAssignToVendor::where([
            ['product_id', $productId],
            ['vendor_id', $vendorId]
        ])->first();

        if ($productVendor) {
            // Toggle product status based on vendor
            $productVendor->update([
                'status' => $productVendor->status == 1 ? 0 : 1
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Removed from wishlist.',
        ]);
    } else {
        // Create a new wishlist entry with status 1
        $wishlist = Wishlist::create([
            'store_manager_id' => $storeManagerId,
            'store_id' => $storeId,
            'product_id' => $productId,
            'vendor_id' => $vendorId,
            'status' => 1
        ]);

        // Update the product status to 1 for this vendor
        $productVendor = ProductAssignToVendor::where([
            ['product_id', $productId],
            ['vendor_id', $vendorId]
        ])->first();

        if ($productVendor) {
            $productVendor->update(['status' => 1]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Added to wishlist successfully.',
            'wishlist' => $wishlist
        ]);
    }
}





    // public function getWishlist(Request $request)
    // {
    //     try {
    //         $query = Wishlist::query()
    //             ->leftJoin('product_assign_to_vendors', function ($join) {
    //                 $join->on('wishlists.product_id', '=', 'product_assign_to_vendors.product_id')
    //                      ->on('wishlists.vendor_id', '=', 'product_assign_to_vendors.vendor_id');
    //             })
    //             ->select('wishlists.*', 'product_assign_to_vendors.product_price');

    //         if ($request->has('store_manager_id')) {
    //             $query->where('wishlists.store_manager_id', $request->input('store_manager_id'));
    //         }

    //         if ($request->has('store_id')) {
    //             $query->where('wishlists.store_id', $request->input('store_id'));
    //         }

    //         if ($request->has('product_id')) {
    //             $query->where('wishlists.product_id', $request->input('product_id'));
    //         }

    //         if ($request->has('vendor_id')) {
    //             $query->where('wishlists.vendor_id', $request->input('vendor_id'));
    //         }

    //         $wishlist = $query->with(['vendor', 'product.productImage'])->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Wishlist retrieved successfully',
    //             'wishlist' => $wishlist
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error retrieving wishlist',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function getWishlist(Request $request)
    // {
    //     try {
    //         $query = Wishlist::query()
    //             ->leftJoin('product_assign_to_vendors', function ($join) {
    //                 $join->on('wishlists.product_id', '=', 'product_assign_to_vendors.product_id')
    //                 ->on('wishlists.vendor_id', '=', 'product_assign_to_vendors.vendor_id');

    //             })
    //             ->select('wishlists.*', 'product_assign_to_vendors.product_price');

    //         if ($request->has('store_manager_id')) {
    //             $query->where('wishlists.store_manager_id', $request->input('store_manager_id'));
    //         }

    //         if ($request->has('store_id')) {
    //             $query->where('wishlists.store_id', $request->input('store_id'));
    //         }


    //         $wishlist = $query->with(['vendor','product.productImage'])->get();

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Wishlist retrieved successfully',
    //             'wishlist' => $wishlist
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error retrieving wishlist',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

//     public function getWishlist(Request $request)
// {
//     try {
//         $query = Wishlist::query()
//             ->leftJoin('product_assign_to_vendors', function ($join) {
//                 $join->on('wishlists.product_id', '=', 'product_assign_to_vendors.product_id')
//                      ->on('wishlists.vendor_id', '=', 'product_assign_to_vendors.vendor_id');
//             })
//             ->select('wishlists.*', 'product_assign_to_vendors.product_price');

//         if ($request->has('store_manager_id')) {
//             $query->where('wishlists.store_manager_id', $request->input('store_manager_id'));
//         }

//         if ($request->has('store_id')) {
//             $query->where('wishlists.store_id', $request->input('store_id'));
//         }

//         $wishlist = $query->with(['vendor','product.productImage'])->get();

//         // Add 'is_in_wishlist' based on the 'status' value
//         $wishlist = $wishlist->map(function ($item) {
//             $item->is_in_wishlist = $item->status == 1 ? true : false;
//             return $item;
//         });

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Wishlist retrieved successfully',
//             'wishlist' => $wishlist
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'Error retrieving wishlist',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

public function getWishlist($storeManagerId, $storeId)
{
    try {
        // Query to check if the store_id and store_manager_id exist in the wishlist table
        $exists = Wishlist::where('store_manager_id', $storeManagerId)
                          ->where('store_id', $storeId)
                          ->exists();

        if (!$exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'No wishlist data found for the provided Store ID and Store Manager ID',
            ]);
        }

        // Main query to get the wishlist
        $wishlist = Wishlist::query()
            ->leftJoin('product_assign_to_vendors', function ($join) {
                $join->on('wishlists.product_id', '=', 'product_assign_to_vendors.product_id')
                     ->on('wishlists.vendor_id', '=', 'product_assign_to_vendors.vendor_id');
            })
            ->select('wishlists.*', 'product_assign_to_vendors.product_price')
            ->where('wishlists.store_manager_id', $storeManagerId)
            ->where('wishlists.store_id', $storeId)
            ->with(['vendor', 'product.productImage'])
            ->get();

        // Add 'is_in_wishlist' based on the 'status' value
        $wishlist = $wishlist->map(function ($item) {
            $item->is_in_wishlist = $item->status == 1 ? true : false;
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Wishlist retrieved successfully',
            'wishlist' => $wishlist
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error retrieving wishlist',
            'error' => $e->getMessage()
        ], 500);
    }
}



}

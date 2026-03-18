<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Orders;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StoreHasSalesManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\VendorOrderInvoiceMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ManagerOrderInvoiceMail;
use App\Models\ProductAssignToVendor;

class OrderController extends Controller
{

    private function generateUniqueProductId()
    {
        do {
            $orderCode = $this->generateRandomProductId();
        } while (Orders::where('order_code', $orderCode)->exists());

        return $orderCode;
    }

    private function generateRandomProductId($length = 10)
    {
        $characters = '0123456789';
        $orderCode = '';

        for ($i = 0; $i < $length; $i++) {
            $orderCode .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $orderCode;
    }



    // public function store(Request $request)
    // {
    //     try {
    //         // Format the date
    //         $date = $request->date; // Retrieve the date from the request
    //         $formattedDate = Carbon::createFromFormat('d-m-Y', $date)->format('d-m-y');

    //         // Create the order
    //         $order = Orders::create([
    //             'store_manager_id' => $request->store_manager_id,
    //             'store_id' => $request->store_id,
    //             'vendor_id' => $request->vendor_id,
    //             'total_quantity' => $request->total_quantity,
    //             'total_price' => $request->total_price,
    //             'invoice_number' => $request->invoice_number,
    //             'status' => $request->status ?? 'In-Progress',
    //             'store_manager_name' => $request->store_manager_name,
    //             'store_name' => $request->store_name,
    //             'vendor_name' => $request->vendor_name,
    //             'date' => $formattedDate,
    //             'order_code' => $this->generateUniqueProductId(),
    //         ]);

    //         // Ensure products is an array
    //         $products = json_decode($request->products, true);
    //         if (!$products || !is_array($products)) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Invalid or missing products data',
    //             ]);
    //         }

    //         $productDetails = [];
    //         foreach ($products as $product) {
    //             $productData = Product::select('id', 'product_name', 'price')
    //                 ->where('id', $product['product_id'])
    //                 ->firstOrFail()
    //                 ->toArray();

    //             $productDetails[] = [
    //                 'product_id' => $productData['id'],
    //                 'product_name' => $productData['product_name'],
    //                 'quantity' => $product['quantity'],
    //                 'price' => $product['price'],
    //                 'sub_total' => $product['quantity'] * $product['price'], // Calculate sub_total
    //                 'image' => $product['image'],
    //             ];


    //             $orderItem = OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $product['product_id'],
    //                 'quantity' => $product['quantity'],
    //                 'price' => $product['price'],
    //                 'product_name' => $productData['product_name'], // Fixed key
    //                 'image' => $product['image'], // Fixed key
    //                 'sub_total' => $product['quantity'] * $product['price'], // Calculate sub_total
    //             ]);
    //         }


    //         $storeManager = User::find($request->store_manager_id);
    //         $storeManagerEmail = $storeManager ? $storeManager->email : null;

    //         $vendor = Vendor::find($request->vendor_id);
    //         $vendorEmail = $vendor ? $vendor->email : null;

    //         // Check if emails are found
    //         if (!$storeManagerEmail || !$vendorEmail) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Store Manager or Vendor email not found',
    //             ]);
    //         }

    //         // Send the invoice email to the store manager
    //         Mail::to($storeManagerEmail)->send(new ManagerOrderInvoiceMail($order, $productDetails));

    //         // Send the invoice email to the vendor
    //         Mail::to($vendorEmail)->send(new VendorOrderInvoiceMail($order, $productDetails));

    //         return response()->json(['message' => 'Order created successfully', 'order' => $order]);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
    //     }
    // }
 
    public function store(Request $request)
    {
        // return $request;
        try {
            // Format the date
            $date = $request->date;
            $formattedDate = Carbon::createFromFormat('d-m-Y', $date)->format('d-m-y');

            // Create the order
            $order = Orders::create([        
                'store_manager_id' => $request->store_manager_id,
                'store_id' => $request->store_id,      
                'vendor_id' => $request->vendor_id,
                'total_quantity' => $request->total_quantity,
                'total_price' => $request->total_price,
                'invoice_number' => $request->invoice_number,
                'status' => $request->status,
                'store_manager_name' => $request->store_manager_name,
                'store_name' => $request->store_name,
                'vendor_name' => $request->vendor_name,
                'date' => $formattedDate,
                'order_code' => $this->generateUniqueProductId(),
                'store_address' => $request->store_address,
                'store_phone_no' => $request->store_phone_no,
            ]); 

            // return $request;

            // Ensure products is an array
            $products = json_decode($request->products, true);
            if (!$products || !is_array($products)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or missing products data',
                ]);
            }

            $productDetails = [];
            foreach ($products as $product) {
                $productData = Product::select('id', 'product_name', 'price')
                    ->where('id', $product['product_id'])
                    ->firstOrFail()
                    ->toArray();

                // return  $priceAfterDiscount;

                $discountPercentage = isset($product['discount']) ? $product['discount'] : 0;

                $subTotal = $product['quantity'] * $product['price'];

                $discountAmount = ($discountPercentage / 100) * $subTotal;

                $subTotalAfterDiscount = $subTotal - $discountAmount;

                $productDetails[] = [
                    'product_id' => $productData['id'],
                    'product_name' => $productData['product_name'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount_price' => $product['discount'],
                    'sub_total' =>  $subTotal,
                    'sub_total_after_discount' => $subTotalAfterDiscount,
                    'image' => $product['image'],
                    'discount_amount' => $discountAmount
                ];


                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount_price' => $product['discount'],
                    'product_name' => $productData['product_name'],
                    'image' => $product['image'],
                    'sub_total' => $subTotal,
                    'priceAfterDiscount' =>  $subTotalAfterDiscount,
                    'discount_amount' => $discountAmount
                ]);
            }

            // Check status before sending emails
            if ($request->status !== 'pending') {
                $storeManager = User::find($request->store_manager_id);
                $storeManagerEmail = $storeManager ? $storeManager->email : null;

                $vendor = Vendor::find($request->vendor_id);
                $vendorEmail = $vendor ? $vendor->email : null;

                $salesManagerData = StoreHasSalesManager::where('store_manager_id', $storeManager->id)->where('whole_seller_id', $vendor->id)->where('store_id', $request->store_id)->first();
                // // Check if emails are found
                // if (!$storeManagerEmail || !$vendorEmail) {
                //     return response()->json([
                //         'status' => 'error',
                //         'message' => 'Store Manager or Vendor email not found',
                //     ]);
                // }

                $emailData = [
                    'order' => $order,
                    'productDetails' => $productDetails,  // Pass discount and price-after-discount details
                ];

                // return $emailData;


                // Send the invoice email to the store manager
                Mail::to($storeManagerEmail)->send(new ManagerOrderInvoiceMail($emailData));
                // return $order;
                if ($vendorEmail != null && $salesManagerData->sales_manager_email != null) {
                    Mail::to($vendorEmail)->send(new VendorOrderInvoiceMail($emailData));
                    Mail::to($salesManagerData->sales_manager_email)->send(new VendorOrderInvoiceMail($emailData));
                }elseif($vendorEmail != null && $salesManagerData->sales_manager_email == null) {
                     Mail::to($vendorEmail)->send(new VendorOrderInvoiceMail($emailData));  
                }elseif($vendorEmail == null && $salesManagerData->sales_manager_email != null) {
                    Mail::to($salesManagerData->sales_manager_email)->send(new VendorOrderInvoiceMail($emailData));
                }
                
                // Send the invoice email to the vendor
                // Mail::to($vendorEmail)->send(new VendorOrderInvoiceMail($emailData));
                // return $vendorEmail;
            }

            return response()->json(['message' => 'Order created successfully', 'order' => $order]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 500);
        }
    }



    public function index($storeManagerId, $storeId)
    {
        try {
            $userOrderItems = Orders::with('vendor:id,vendor_name,image', 'orderItem')
                ->where('store_manager_id', $storeManagerId) // Filter by store manager ID
                ->where('store_id', $storeId) // Filter by store manager ID
                ->whereIn('status', ['In-Progress', 'Completed']) // Filter by status
                ->orderByRaw("CASE status WHEN 'In-Progress' THEN 1 ELSE 2 END") // Prioritize 'In-Progress' status
                ->latest() // Order by latest date
                ->get();
            if (!$userOrderItems) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User carts Info Not Found',
                    'user_carts' =>  $userOrderItems,
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'My Orders retrieved successfully',
                    'my_orders' =>  $userOrderItems,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
    // public function index($storeManagerId)
    // {
    //     try {
    //         $userOrderItems = Orders::with('vendor:id,vendor_name,image', 'orderItem')
    //             ->where('store_manager_id', $storeManagerId) // Filter by store manager ID
    //             ->whereIn('status', ['In-Progress', 'Completed']) // Filter by status
    //             ->orderByRaw("CASE status WHEN 'In-Progress' THEN 1 ELSE 2 END") // Prioritize 'In-Progress' status
    //             ->orderBy('created_at', 'desc') // Order by creation date within each status group
    //             ->get();

    //         // Check if userOrderItems is empty
    //         if ($userOrderItems->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'User orders not found',
    //                 'user_orders' =>  $userOrderItems,
    //             ], 404);
    //         } else {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'My orders retrieved successfully',
    //                 'my_orders' =>  $userOrderItems,
    //             ], 200);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function saveOrder($storeManagerId, $storeId)
    {
        try {
            // $userOrderItems = OrderItem::whereHas('order', function ($query) use ($storeManagerId) {
            //     $query->where('store_manager_id', $storeManagerId)
            //         ->where('status', 'pending'); // Add the status filter here
            // })
            //     ->with('order')
            //     ->orderBy('created_at' , 'desc')
            //     ->get();
            $userOrderItems = Orders::with('vendor:id,vendor_name,image', 'orderItem')
                ->where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->where('status', 'pending')
                ->latest()
                ->get();

            if ($userOrderItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User carts Info Not Found',
                    'user_carts' => $userOrderItems,
                ], 404);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Save Orders retrieved successfully',
                    'save_Orders' => $userOrderItems,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    // public function getFrequentOrders($storeManagerId, $storeId)
    // {
    //     try {
    //         // Fetch completed orders for the given store manager with vendor and product details
    //         $orders = Orders::where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('status', 'Completed')
    //             ->with(['orderItem.product']) // Ensure product details are loaded
    //             ->get();

    //         if ($orders->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'failed',
    //                 'message' => 'No data found!'
    //             ]);
    //         }

    //         // Initialize arrays to store product counts and vendor details
    //         $productCounts = [];

    //         foreach ($orders as $order) {
    //             foreach ($order->orderItem as $item) {
    //                 $productId = $item->product_id;

    //                 // Aggregate product data
    //                 if (!isset($productCounts[$productId])) {
    //                     $productCounts[$productId] = [
    //                         'product_id' => $item->product->id,
    //                         'product_name' => $item->product->product_name,
    //                         'product_images' => $item->image, // Make sure this matches your database field
    //                         'product_price' => $item->price,
    //                         'store_manager_id' => $order->store_manager_id,
    //                         'store_manager_name' => $order->store_manager_name,
    //                         'store_id' => $order->store_id,
    //                         'store_name' => $order->store_name,
    //                         'count' => 0,
    //                         'vendor_id' => $order->vendor_id,
    //                         'vendor_name' => $order->vendor_name
    //                     ];
    //                 }

    //                 // Increment the count for each occurrence of product_id
    //                 $productCounts[$productId]['count'] += $item->quantity; // Use $item->quantity if available, otherwise use 1

    //                 // Aggregate vendor details if needed

    //             }
    //         }

    //         // Convert the associative array to a numerically indexed array
    //         $productCountsArray = array_values($productCounts);

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => [
    //                 'products' => $productCountsArray, // Use array instead of object
    //             ]
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'An error occurred: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    public function getFrequentOrders($storeManagerId, $storeId)
    {
        try {
            // Fetch completed orders for the given store manager with vendor and product details
            $orders = Orders::where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->where('status', 'Completed')
                ->with(['orderItem.product']) // Ensure product details are loaded
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No data found!'
                ]);
            }

            // Initialize arrays to store product counts and vendor details
            $productCounts = [];

            foreach ($orders as $order) {
                foreach ($order->orderItem as $item) {
                    $productId = $item->product_id;

                    // Aggregate product data
                    if (!isset($productCounts[$productId])) {
                        $productCounts[$productId] = [
                            'product_id' => $item->product->id,
                            'product_name' => $item->product->product_name,
                            'product_images' => $item->image, // Make sure this matches your database field
                            'product_price' => $item->price,
                            'general_discount' => $item->discount_price, // Include general discount here
                            'store_manager_id' => $order->store_manager_id,
                            'store_manager_name' => $order->store_manager_name,
                            'store_id' => $order->store_id,
                            'store_name' => $order->store_name,
                            'count' => 0, // Initialize count to 0
                            'vendor_id' => $order->vendor_id,
                            'vendor_name' => $order->vendor_name
                        ];
                    }

                    // Increment the count for each occurrence of product_id
                    $productCounts[$productId]['count'] += 1; // Increment by 1 for each occurrence
                }
            }

            // Convert the associative array to a numerically indexed array
            $productCountsArray = array_values($productCounts);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'products' => $productCountsArray, // Use array instead of object
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }


    public function orderStatus(Request $request)
    {
        try {
            $orderId = $request->input('orderId');
            $storeManagerId = $request->input('storeManagerId');

            // Find the order with the given order code, store manager ID, and status 'pending'
            $order = Orders::where('order_code', $orderId)
                ->where('store_manager_id', $storeManagerId)
                ->where('status', 'pending')
                ->first();

            // Check if the order exists
            if ($order === null) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Order not found',
                    'order' => $order,
                ], 404);
            }

            // Update the order status and created_at timestamp
            $order->status = 'In-Progress';
            $order->created_at = now(); // Set the current date and time
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order status changed successfully',
                'order' => $order,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }




    // public function getRecommendedProduct($storeManagerId, $storeId, $vendorId)
    // {
    //     try {
    //         // Get product data with vendor and product images
    //         $products = ProductAssignToVendor::with('product.productImage', 'vendor')
    //             ->where('vendor_id', $vendorId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->get();

    //         // Get the last two completed orders for the specified store manager, store, and vendor
    //         $lastTwoOrders = Orders::where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('vendor_id', $vendorId)
    //             ->where('status', 'completed')
    //             ->with('orderItem') // Include all order items in the last two orders
    //             ->orderBy('created_at', 'desc')
    //             ->take(2)
    //             ->get();

    //         // If no orders are found, return 0 as recommended quantity for all products
    //         if ($lastTwoOrders->isEmpty()) {
    //             $products->each(function ($product) {
    //                 $product->recommended_quantity = 0;
    //             });

    //             return response()->json([
    //                 'status' => 'success',
    //                 'products' => $products,
    //             ]);
    //         }

    //         // Prepare an array to hold the total quantities for each product
    //         $productQuantities = [];

    //         // Loop through the last two orders and collect the quantities for each product
    //         foreach ($lastTwoOrders as $order) {
    //             foreach ($order->orderItem as $orderItem) {
    //                 $productId = $orderItem->product_id;
    //                 if (!isset($productQuantities[$productId])) {
    //                     $productQuantities[$productId] = [];
    //                 }

    //                 // Push the quantity of the product into the array
    //                 $productQuantities[$productId][] = $orderItem->quantity;
    //             }
    //         }

    //         // Calculate the recommended quantity for each product
    //         foreach ($productQuantities as $productId => $quantities) {
    //             $totalQuantity = array_sum($quantities);
    //             $totalOrders = count($quantities);
    //             $recommendedQuantity = ($totalOrders > 0) ? ceil($totalQuantity / $totalOrders) : 0;

    //             // Attach the recommended quantity to the product data
    //             $products->each(function ($product) use ($productId, $recommendedQuantity) {
    //                 if ($product->product_id == $productId) {
    //                     $product->recommended_quantity = $recommendedQuantity;
    //                 }
    //             });
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'products' => $products,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // public function getRecommendedProduct($storeManagerId, $storeId, $vendorId)
    // {
    //     try {
    //         // Get the last two completed orders for the specified store manager, store, and vendor
    //         $lastTwoOrders = Orders::where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('vendor_id', $vendorId)
    //             ->where('status', 'completed')
    //             ->with('orderItem') // Include all order items in the last two orders
    //             ->orderBy('created_at', 'desc')
    //             ->take(2)
    //             ->get();
    //         $count = $lastTwoOrders->count();

    //         // Check if at least 2 completed orders are found
    //         if ($count == 0) {
    //             // return "ok";
    //             $lastTwoOrdersOthers = Orders::where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('status', 'completed')
    //             ->with('orderItem') // Include all order items in the last two orders
    //             ->orderBy('created_at', 'desc')
    //             ->take(2)
    //             ->get();

    //             $count = 0;
    //             foreach($lastTwoOrdersOthers as $lastTwoOrdersOther)
    //             {
    //                 // return $lastTwoOrdersOther;
    //                 // $count = $count + 1;
    //                 $orderItems = OrderItem::where('order_id',$lastTwoOrdersOther->id)->get();
    //                 // return $orderItems;
    //                 foreach($orderItems as $orderItem)
    //                 {
    //                     $count = $count + $orderItem->quantity;
    //                     $assignProduct = ProductAssignToVendor::where('vendor_id',$vendorId)
    //                     ->where('product_id',$orderItem->product_id)
    //                     ->first();
    //                     if($assignProduct)
    //                     {
    //                                 // Extract product IDs from the last two orders
    //                                 $orderedProductIds = [];
    //                                 $orderedProductIds[] = $orderItem->product_id;
    //                                 // foreach ($lastTwoOrders as $order) {
    //                                 //     foreach ($order->orderItem as $orderItem) {
    //                                 //         $orderedProductIds[] = $orderItem->product_id;
    //                                 //     }
    //                                 // }

    //                                 // Get all vendors offering the products that were ordered

    //                     }
    //                 }
    //                 $productsAcrossVendors = ProductAssignToVendor::with('product.productImage', 'vendor')
    //                 ->where('store_manager_id', $storeManagerId)
    //                 ->where('store_id', $storeId)
    //                 ->whereIn('product_id', $orderedProductIds) // Filter products that were ordered
    //                 ->get();

    //             // Prepare an array to hold the total quantities for each product
    //             $productQuantities = [];

    //             // Loop through the last two orders and collect the quantities for each product
    //             foreach ($lastTwoOrders as $order) {
    //                 foreach ($order->orderItem as $orderItem) {
    //                     $productId = $orderItem->product_id;
    //                     if (!isset($productQuantities[$productId])) {
    //                         $productQuantities[$productId] = [];
    //                     }

    //                     // Push the quantity of the product into the array
    //                     $productQuantities[$productId][] = $orderItem->quantity;
    //                 }
    //             }

    //             // Calculate the recommended quantity for each product
    //             foreach ($productQuantities as $productId => $quantities) {
    //                 $totalQuantity = array_sum($quantities);
    //                 $totalOrders = count($quantities);
    //                 $recommendedQuantity = ($totalOrders > 0) ? ceil($totalQuantity / $totalOrders) : 0;

    //                 // Attach the recommended quantity to all vendors offering this product
    //                 $productsAcrossVendors->each(function ($product) use ($productId, $recommendedQuantity) {
    //                     if ($product->product_id == $productId) {
    //                         $product->recommended_quantity = $recommendedQuantity;
    //                     }
    //                 });
    //             }

    //             // Check if there are products not found in the initial vendors
    //             $missingProductIds = array_diff($orderedProductIds, $productsAcrossVendors->pluck('product_id')->toArray());

    //             if (!empty($missingProductIds)) {
    //                 // Fetch other vendors offering the missing products
    //                 $otherVendors = ProductAssignToVendor::with('product.productImage', 'vendor')
    //                     ->where('store_manager_id', $storeManagerId)
    //                     ->where('store_id', $storeId)
    //                     ->whereIn('product_id', $missingProductIds) // Filter missing products
    //                     ->get();

    //                 // Merge with the initial products across vendors
    //                 $productsAcrossVendors = $productsAcrossVendors->merge($otherVendors);
    //             }

    //             return response()->json([
    //                 'status' => 'success',
    //                 'productss' => $productsAcrossVendors, // Return products with vendors and recommended quantities
    //             ]);
    //             }

    //             // // Define alternative logic or return a specific response
    //             // // For example, fetch all products for the vendor and return default quantities
    //             // $productsAcrossVendors = ProductAssignToVendor::with('product.productImage', 'vendor')
    //             //     ->where('store_manager_id', $storeManagerId)
    //             //     ->where('store_id', $storeId)
    //             //     ->where('vendor_id', $vendorId)
    //             //     ->get();

    //             // // Return products with a default recommended quantity or other relevant info
    //             // return response()->json([
    //             //     'status' => 'success',
    //             //     'products' => $productsAcrossVendors->map(function ($product) {
    //             //         $product->recommended_quantity = 0; // Default or alternative quantity
    //             //         return $product;
    //             //     }),
    //             // ]);
    //         }

    //         // Extract product IDs from the last two orders
    //         $orderedProductIds = [];
    //         foreach ($lastTwoOrders as $order) {
    //             foreach ($order->orderItem as $orderItem) {
    //                 $orderedProductIds[] = $orderItem->product_id;
    //             }
    //         }

    //         // Get all vendors offering the products that were ordered
    //         $productsAcrossVendors = ProductAssignToVendor::with('product.productImage', 'vendor')
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->whereIn('product_id', $orderedProductIds) // Filter products that were ordered
    //             ->get();

    //         // Prepare an array to hold the total quantities for each product
    //         $productQuantities = [];

    //         // Loop through the last two orders and collect the quantities for each product
    //         foreach ($lastTwoOrders as $order) {
    //             foreach ($order->orderItem as $orderItem) {
    //                 $productId = $orderItem->product_id;
    //                 if (!isset($productQuantities[$productId])) {
    //                     $productQuantities[$productId] = [];
    //                 }

    //                 // Push the quantity of the product into the array
    //                 $productQuantities[$productId][] = $orderItem->quantity;
    //             }
    //         }

    //         // Calculate the recommended quantity for each product
    //         foreach ($productQuantities as $productId => $quantities) {
    //             $totalQuantity = array_sum($quantities);
    //             $totalOrders = count($quantities);
    //             $recommendedQuantity = ($totalOrders > 0) ? ceil($totalQuantity / $totalOrders) : 0;

    //             // Attach the recommended quantity to all vendors offering this product
    //             $productsAcrossVendors->each(function ($product) use ($productId, $recommendedQuantity) {
    //                 if ($product->product_id == $productId) {
    //                     $product->recommended_quantity = $recommendedQuantity;
    //                 }
    //             });
    //         }

    //         // Check if there are products not found in the initial vendors
    //         $missingProductIds = array_diff($orderedProductIds, $productsAcrossVendors->pluck('product_id')->toArray());

    //         if (!empty($missingProductIds)) {
    //             // Fetch other vendors offering the missing products
    //             $otherVendors = ProductAssignToVendor::with('product.productImage', 'vendor')
    //                 ->where('store_manager_id', $storeManagerId)
    //                 ->where('store_id', $storeId)
    //                 ->whereIn('product_id', $missingProductIds) // Filter missing products
    //                 ->get();

    //             // Merge with the initial products across vendors
    //             $productsAcrossVendors = $productsAcrossVendors->merge($otherVendors);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'products' => $productsAcrossVendors, // Return products with vendors and recommended quantities
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // public function getRecommendedProduct($storeManagerId, $storeId, $vendorId)
    // {
    //     try {
    //         // Get the last two completed orders for the specified store manager, store, and vendor
    //         $lastTwoOrders = Orders::where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->where('vendor_id', $vendorId)
    //             ->where('status', 'completed')
    //             ->with('orderItem') // Include all order items in the last two orders
    //             ->orderBy('created_at', 'desc')
    //             ->take(2)
    //             ->get();

    //         $count = count($lastTwoOrders);

    //         if ($count < 2) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'You must have at least 2 completed orders to proceed',
    //             ]); // Use 400 status for a bad request
    //         }
    //         // If no orders are found, return 0 as recommended quantity for all products
    //         if ($lastTwoOrders->isEmpty()) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'products' => []
    //             ]);
    //         }

    //         // Extract product IDs from the last two orders
    //         $orderedProductIds = [];
    //         foreach ($lastTwoOrders as $order) {
    //             foreach ($order->orderItem as $orderItem) {
    //                 $orderedProductIds[] = $orderItem->product_id;
    //             }
    //         }

    //         // Get only the products that were in the last two orders
    //         $products = ProductAssignToVendor::with('product.productImage', 'vendor')
    //             ->where('vendor_id', $vendorId)
    //             ->where('store_manager_id', $storeManagerId)
    //             ->where('store_id', $storeId)
    //             ->whereIn('product_id', $orderedProductIds) // Filter products that were ordered
    //             ->get();

    //         // Prepare an array to hold the total quantities for each product
    //         $productQuantities = [];

    //         // Loop through the last two orders and collect the quantities for each product
    //         foreach ($lastTwoOrders as $order) {
    //             foreach ($order->orderItem as $orderItem) {
    //                 $productId = $orderItem->product_id;
    //                 if (!isset($productQuantities[$productId])) {
    //                     $productQuantities[$productId] = [];
    //                 }

    //                 // Push the quantity of the product into the array
    //                 $productQuantities[$productId][] = $orderItem->quantity;
    //             }
    //         }

    //         // Calculate the recommended quantity for each product
    //         foreach ($productQuantities as $productId => $quantities) {
    //             $totalQuantity = array_sum($quantities);
    //             $totalOrders = count($quantities);
    //             $recommendedQuantity = ($totalOrders > 0) ? ceil($totalQuantity / $totalOrders) : 0;

    //             // Attach the recommended quantity to the product data
    //             $products->each(function ($product) use ($productId, $recommendedQuantity) {
    //                 if ($product->product_id == $productId) {
    //                     $product->recommended_quantity = $recommendedQuantity;
    //                 }
    //             });
    //         }

    //         $storeManagerName = $lastTwoOrders[0]->store_manager_name ?? null;
    //         $storeName = $lastTwoOrders[0]->store_name ?? null;

    //         return response()->json([
    //             'status' => 'success',
    //             'store_manager_name' => $storeManagerName,
    //             'store_name' => $storeName,
    //             'products' => $products,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function getRecommendedProduct($storeManagerId, $storeId, $vendorId)
    {
        try {
            // Fetch the last two completed orders for the specified store manager, store, and vendor
            $lastTwoOrders = Orders::where('store_manager_id', $storeManagerId)
                ->where('store_id', $storeId)
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->with('orderItem')
                ->get();

            // Initialize arrays for storing product IDs and quantities
            $orderedProductIds = [];
            $productQuantities = [];

            // If there are completed orders
            if ($lastTwoOrders->isNotEmpty()) {
                foreach ($lastTwoOrders as $order) {
                    foreach ($order->orderItem as $orderItem) {
                        $productId = $orderItem->product_id;
                        $orderedProductIds[] = $productId;
                        // Collect quantities of each product
                        if (!isset($productQuantities[$productId])) {
                            $productQuantities[$productId] = [];
                        }
                        $productQuantities[$productId][] = $orderItem->quantity;
                    }
                }
                // return $productQuantities;
                // Calculate recommended quantities based on last two orders
                $recommendedQuantities = [];
                foreach ($productQuantities as $productId => $quantities) {
                    $totalQuantity = array_sum($quantities);
                    $totalOrders = count($quantities);
                    $recommendedQuantities[$productId] = ($totalOrders > 0) ? ceil($totalQuantity / $totalOrders) : 0;
                }

                // Fetch products assigned to the current vendor
                $productsAssigned = ProductAssignToVendor::with('product.productImage', 'vendor')
                    ->where('store_manager_id', $storeManagerId)
                    ->where('store_id', $storeId)
                    ->where('vendor_id', $vendorId)
                    ->whereIn('product_id', $orderedProductIds)
                    ->get();

                if ($productsAssigned->isEmpty()) {
                    // No assigned products for current vendor, show a message
                    return response()->json([
                        'status' => 'info',
                        'message' => 'No recommended product available for this vendor',
                    ]);
                } else {
                    // Attach recommended quantities to products assigned to the current vendor
                    $productsAssigned->each(function ($product) use ($recommendedQuantities) {
                        if (isset($recommendedQuantities[$product->product_id])) {
                            $product->recommended_quantity = $recommendedQuantities[$product->product_id];
                        } else {
                            $product->recommended_quantity = 0; // Or handle as needed if not recommended
                        }
                    });

                    // Return the products with recommended quantities
                    return response()->json([
                        'status' => 'success',
                        'products' => $productsAssigned,
                    ]);
                }
            } else {
                // No completed orders found
                return response()->json([
                    'status' => 'info',
                    'message' => 'No completed orders found for recommendations.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

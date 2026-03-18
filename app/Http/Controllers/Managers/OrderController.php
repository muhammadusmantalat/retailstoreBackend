<?php

namespace App\Http\Controllers\Managers;

use Carbon\Carbon;
use App\Models\Audit;
use App\Models\Orders;
use App\Models\OrderItem;
use App\Models\checkOrder;
use Illuminate\Http\Request;
use App\Models\ShortCaseReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;

class OrderController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('web')->id();

        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;


        $orders = Orders::where('store_manager_id', $authId)
            ->where('store_id', $StoreId->store_id)
            ->whereIn('status', ['In-progress', 'Completed'])
            ->with('orderitem', 'checkOrder')
            ->orderByRaw("FIELD(status, 'In-progress', 'Completed')")
            ->orderBy('id', 'DESC')
            ->get();        // return $orders;
        //  return $orders;

        return view('managers.orders.index', compact('orders'));
    }

    public function getOrderCount()
    {
        $authId = Auth::guard('web')->id();

        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();

        $orderCount = Orders::where('store_id', $StoreId->store_id)->where('status', 'in-Progress')->count();
        return response()->json(['count' => $orderCount]);
    }

    public function updateStatus(Request $request)
    {
        $order = Orders::findOrFail($request->order_id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('manager.storeManagerOrder.index')->with(['status' => true, 'message' => 'Order Status Updated Successfully']);
        // return $request;
    }

    public function orderDetail($id)
    {
        // return $request;
        $orderItems = OrderItem::where('order_id', $id)->with('product')->latest()->get();
        // return $orderItems;
        return view('managers.ordersItems.index', compact('orderItems'));
    }
    public function showSignAllChecked($orderId)
    {
        // return $orderId;
        $reasons = ShortCaseReason::all();
        $order = Orders::find($orderId);
        $checkOrder = CheckOrder::where('order_id', $orderId)->first();
        // return $checkOrder;
        // session()->flash('message', 'Signed All Checked');
        return view('managers.orders.checkOrder', compact('order', 'reasons', 'checkOrder'));
    }

    // public function completeSignAllChecked(Request $request, $orderId)
    // {
    //     try {
    //         // Validate the request data
    //         $validatedData = $request->validate([
    //             'total_cases' => 'nullable|integer',
    //             'short_cases_status' => 'nullable|boolean',
    //             'short_case_reason' => 'nullable|string',
    //             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             'manager_recepit' => 'nullable|mimes:pdf|max:2048',
    //             'trip_cases_1' => 'nullable|integer',
    //             'trip_cases_2' => 'nullable|integer',
    //             'trip_cases_3' => 'nullable|integer',
    //             'trip_cases_4' => 'nullable|integer',
    //             'trip_cases_5' => 'nullable|integer',
    //             'trip_cases_6' => 'nullable|integer',
    //             'trip_cases_7' => 'nullable|integer',
    //             'trip_cases_8' => 'nullable|integer',
    //             'trip_cases_9' => 'nullable|integer',
    //             'trip_cases_10' => 'nullable|integer',
    //             'checked_by' => 'required|string|max:255',
    //             'invoice_amount' => 'required|numeric',
    //             'payment_method' => 'required|string',
    //             'check_number' => 'nullable|string|max:255',
    //         ]);

    //         // Sum up the received cases from all trips
    //         $receivedCases = array_sum(array_filter([
    //             $request->input('trip_cases_1'),
    //             $request->input('trip_cases_2'),
    //             $request->input('trip_cases_3'),
    //             $request->input('trip_cases_4'),
    //             $request->input('trip_cases_5'),
    //             $request->input('trip_cases_6'),
    //             $request->input('trip_cases_7'),
    //             $request->input('trip_cases_8'),
    //             $request->input('trip_cases_9'),
    //             $request->input('trip_cases_10'),
    //         ]));

    //         // Calculate remaining cases
    //         $remainingCases = ($validatedData['total_cases'] ?? 0) - $receivedCases;

    //         // Handle image upload
    //         $imagePath = null;
    //         if ($request->hasFile('image')) {
    //             $file = $request->file('image');
    //             $filename = time() . '.' . $file->getClientOriginalExtension();
    //             $file->move(public_path('admin/assets/images/users/'), $filename);
    //             $imagePath = 'public/admin/assets/images/users/' . $filename;
    //         }

    //         // Handle receipt upload
    //         $managerRecepit = null;
    //         if ($request->hasFile('manager_recepit')) {
    //             $file = $request->file('manager_recepit');
    //             $store = $file->store('/pdfs', 'public'); // Store in 'public/pdfs' folder
    //             $managerRecepit = 'storage/app/public/' . $store; // Store relative path for public access
    //         }

    //         $deliveryDate = Carbon::now(); // Current date and time


    //         // Create a new record in checkOrder
    //         checkOrder::create([
    //             'order_id' => $orderId,
    //             'total_cases' => $request->total_cases,
    //             'trip_cases_1' => $request->trip_cases_1,
    //             'trip_cases_2' => $request->trip_cases_2,
    //             'trip_cases_3' => $request->trip_cases_3,
    //             'trip_cases_4' => $request->trip_cases_4,
    //             'trip_cases_5' => $request->trip_cases_5,
    //             'trip_cases_6' => $request->trip_cases_6,
    //             'trip_cases_7' => $request->trip_cases_7,
    //             'trip_cases_8' => $request->trip_cases_8,
    //             'trip_cases_9' => $request->trip_cases_9,
    //             'trip_cases_10' => $request->trip_cases_10,
    //             'short_cases_status' => $request->short_cases_status,
    //             'short_case_reason' => $request->short_case_reason,
    //             'image' => $imagePath,
    //             'manager_recepit' => $managerRecepit,
    //             'received_cases' => $receivedCases,
    //             'remaining_cases' => $remainingCases,
    //             'checked_by' => $request->checked_by,
    //             'payment_method' => $request->payment_method,
    //             'check_number' => $request->check_number,
    //             'invoice_amount' => $request->invoice_amount,
    //             'delivery_date' => $deliveryDate
    //         ]);
    //         $order = Orders::where('id', $orderId)->update(['status' => 'Completed']);
    //         if ($order) {
    //             return response()->json(['message' => 'Data saved successfully']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occur' . $e->getMessage()], 500);
    //     }
    // }
    public function completeSignAllChecked(Request $request, $orderId)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'total_cases' => 'nullable|integer',
                'short_cases_status' => 'nullable|boolean',
                'short_case_reason' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'manager_recepit' => 'nullable|mimes:pdf|max:2048',
                'trip_cases_1' => 'nullable|integer',
                'trip_cases_2' => 'nullable|integer',
                'trip_cases_3' => 'nullable|integer',
                'trip_cases_4' => 'nullable|integer',
                'trip_cases_5' => 'nullable|integer',
                'trip_cases_6' => 'nullable|integer',
                'trip_cases_7' => 'nullable|integer',
                'trip_cases_8' => 'nullable|integer',
                'trip_cases_9' => 'nullable|integer',
                'trip_cases_10' => 'nullable|integer',
                'checked_by' => 'required|string|max:255',
                'invoice_amount' => 'required|numeric',
                'payment_method' => 'required|string',
                'check_number' => 'nullable|string|max:255',
            ]);

            // Sum up the received cases from all trips
            $receivedCases = array_sum(array_filter([
                $request->input('trip_cases_1'),
                $request->input('trip_cases_2'),
                $request->input('trip_cases_3'),
                $request->input('trip_cases_4'),
                $request->input('trip_cases_5'),
                $request->input('trip_cases_6'),
                $request->input('trip_cases_7'),
                $request->input('trip_cases_8'),
                $request->input('trip_cases_9'),
                $request->input('trip_cases_10'),
            ]));

            // Calculate remaining cases
            $totalCases = $validatedData['total_cases'] ?? 0;
            $remainingCases = $totalCases - $receivedCases;

            // Handle image upload
            $imagePath = $request->file('image')
                ? $request->file('image')->storeAs('public/admin/assets/images/users', time() . '.' . $request->file('image')->getClientOriginalExtension())
                : null;

            // Handle receipt upload
            $managerRecepitPath = $request->file('manager_recepit')
                ? $request->file('manager_recepit')->store('pdfs', 'public')
                : null;

            $deliveryDate = Carbon::now(); // Current date and time

            // Create a new record in checkOrder
            checkOrder::create(array_filter([
                'order_id' => $orderId,
                'total_cases' => $validatedData['total_cases'],
                'trip_cases_1' => $validatedData['trip_cases_1'] ?? null,
                'trip_cases_2' => $validatedData['trip_cases_2'] ?? null,
                'trip_cases_3' => $validatedData['trip_cases_3'] ?? null,
                'trip_cases_4' => $validatedData['trip_cases_4'] ?? null,
                'trip_cases_5' => $validatedData['trip_cases_5'] ?? null,
                'trip_cases_6' => $validatedData['trip_cases_6'] ?? null,
                'trip_cases_7' => $validatedData['trip_cases_7'] ?? null,
                'trip_cases_8' => $validatedData['trip_cases_8'] ?? null,
                'trip_cases_9' => $validatedData['trip_cases_9'] ?? null,
                'trip_cases_10' => $validatedData['trip_cases_10'] ?? null,
                'short_cases_status' => $validatedData['short_cases_status'] ?? null,
                'short_case_reason' => $validatedData['short_case_reason'] ?? null,
                'image' => $imagePath,
                'manager_recepit' => $managerRecepitPath ? 'storage/' . $managerRecepitPath : null,
                'received_cases' => $receivedCases,
                'remaining_cases' => $remainingCases,
                'checked_by' => $validatedData['checked_by'],
                'payment_method' => $validatedData['payment_method'],
                'check_number' => $validatedData['check_number'] ?? null,
                'invoice_amount' => $validatedData['invoice_amount'],
                'delivery_date' => $deliveryDate
            ]));

            // Update the order status to 'Completed'
            Orders::where('id', $orderId)->update(['status' => 'Completed']);

            return response()->json(['message' => 'Data saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }




    // public function getOrderData($id)
    // {
    //     // Fetch the order with relationships
    //     $order = Orders::with('checkOrder', 'audit')->find($id);

    //     // Check if the order exists
    //     if (!$order) {
    //         return response()->json([
    //             'message' => 'Order not found',
    //             'order_code' => 'No data found',
    //             'vendor_receipt' => 'No data found',
    //             'manager_receipt' => 'No data found',
    //             'description' => 'No data found',
    //             'overcharged_prices' => 'No data found',
    //             'vendor_id' => 'No data found',
    //             'is_vendor_in_audit' => false
    //         ]);
    //     }

    //     // Check if the vendor_id is in the audit
    //     $isVendorInAudit = $order->audit && $order->audit->vendor_id == $order->vendor_id;

    //     return response()->json([
    //         'order_code' => $order->order_code ?? 'No data found',
    //         'vendor_receipt' => $order->checkOrder ? asset($order->checkOrder->image) : 'No data found',
    //         'manager_receipt' => $order->checkOrder ? asset($order->checkOrder->manager_recepit) : 'No data found',
    //         'description' => $order->audit->description ?? '',
    //         'overcharged_prices' => $order->audit->overcharged_prices ?? 'No data found',
    //         'vendor_id' => $order->vendor_id ?? 'No data found',
    //         'is_vendor_in_audit' => $isVendorInAudit
    //     ]);
    // }


    public function getOrderData($id)
    {
        $order = Orders::with('checkOrder', 'audit')->find($id);
        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
                'order_code' => 'No data found',
                'vendor_receipt' => 'No data found',
                'manager_receipt' => 'No data found',
                'description' => 'No data found',
                'overcharged_prices' => 'No data found',
                'vendor_id' => 'No data found',
                'is_vendor_in_audit' => false
            ]);
        }

        $isVendorInAudit = $order->audit && $order->audit->vendor_id == $order->vendor_id;

        return response()->json([
            'order_code' => $order->order_code ?? 'No data found',
            'vendor_receipt' => $order->checkOrder && $order->checkOrder->image ? asset($order->checkOrder->image) : 'No data found',
            'manager_receipt' => $order->checkOrder && $order->checkOrder->manager_recepit ? asset($order->checkOrder->manager_recepit) : 'No data found',
            'description' => $order->audit->description ?? '',
            'overcharged_prices' => $order->audit->overcharged_prices ?? 'No data found',
            'vendor_id' => $order->vendor_id ?? 'No data found',
            'is_vendor_in_audit' => $isVendorInAudit
        ]);
    }
}

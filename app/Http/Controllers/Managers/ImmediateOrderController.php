<?php

namespace App\Http\Controllers\managers;

use Illuminate\Http\Request;
use App\Models\ImmediateOrder;
use App\Models\ShortCaseReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;
use Carbon\Carbon;


class ImmediateOrderController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('web')->id();

        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $imidateOrders = ImmediateOrder::where('store_manager_id', $authId)
        ->where('store_id', $storeId)
        ->latest()->get();
        // return $imidateOrders;

        // $orders = Orders::where('store_manager_id', $authId)
        //     ->where('store_id', $StoreId->store_id)
        //     ->whereIn('status', ['In-progress', 'Completed'])
        //     ->with('orderitem')
        //     ->orderByRaw("FIELD(status, 'In-progress', 'Completed')")
        //     ->orderBy('id', 'DESC')
        //     ->get();        // return $orders;


        return view('managers.immediateOrders.index',compact('imidateOrders'));
    }

    public function create()
    {
        $authId = Auth::guard('web')->id();

        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $reasons = ShortCaseReason::all();

        return view('managers.immediateOrders.create', compact('reasons'));
    }

    public function imediateSignAllChecked(Request $request)
    {
        $authId = Auth::guard('web')->id();

        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        // return $request;
        // Validate the request data
        $validatedData = $request->validate([
            'total_cases' => 'nullable|integer',
            'short_cases_status' => 'nullable|boolean',
            'short_case_reason' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'vendor_name' => 'required',
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
        $remainingCases = ($validatedData['total_cases'] ?? 0) - $receivedCases;

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $imagePath = 'public/admin/assets/images/users/' . $filename;
        }


        $orderDate = Carbon::now()->format('Y-m-d'); // Format to 'Y-m-d'

        // Create a new record in checkOrder
        ImmediateOrder::create([
            'total_cases' => $request->total_cases,
            'trip_cases_1' => $request->trip_cases_1,
            'trip_cases_2' => $request->trip_cases_2,
            'trip_cases_3' => $request->trip_cases_3,
            'trip_cases_4' => $request->trip_cases_4,
            'trip_cases_5' => $request->trip_cases_5,
            'trip_cases_6' => $request->trip_cases_6,
            'trip_cases_7' => $request->trip_cases_7,
            'trip_cases_8' => $request->trip_cases_8,
            'trip_cases_9' => $request->trip_cases_9,
            'trip_cases_10' => $request->trip_cases_10,
            'short_cases_status' => $request->short_cases_status,
            'short_case_reason' => $request->short_case_reason,
            'vendor_recepit' => $imagePath,
            'received_cases' => $receivedCases,
            'remaining_cases' => $remainingCases,
            'checked_by' => $request->checked_by,
            'payment_method' => $request->payment_method,
            'check_number' => $request->check_number,
            'invoice_amount' => $request->invoice_amount,
            'vendor_name' => $request->vendor_name,
            'order_date' => $orderDate,  // Saving the order date
            'store_manager_id' => $authId,
            'store_id' => $storeId,

        ]);
        // return $request;

        return redirect()->route('manager.immediateOrder.index')
        ->with(['status' => true, 'message' => 'Immediate Order Singed Successfully']);
    }

    public function showSignAllChecked($orderId)
    {
        // return $orderId;
        $reasons = ShortCaseReason::all();
        $imidateOrder = ImmediateOrder::where('id', $orderId)->first();
        // return $imidateOrder;

        // session()->flash('message', 'Signed All Checked');
        return view('managers.immediateOrders.show', compact('reasons','imidateOrder'));
    }
}

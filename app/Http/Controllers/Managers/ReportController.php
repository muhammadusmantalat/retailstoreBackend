<?php

namespace App\Http\Controllers\Managers;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Vendor;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignVendorToDepartment;
use App\Models\checkOrder;
use App\Models\StoreManagerStoreDepartment;

class ReportController extends Controller
{
    public function salesIndex()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $vendors = vendor::all()->pluck('vendor_name', 'id');

        return view('managers.sales.index', compact('vendors'));
    }

    public function getSalesData(Request $request)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        // Initialize query for completed orders with order items
        $query = Orders::with('orderItem', 'checkOrder')
            ->where('store_manager_id', $authId)
            ->where('store_id', $storeId)
            ->where('status', 'Completed');  // Fetch only completed orders
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by period
        switch ($request->period) {
            case 'daily':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'weekly':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'yearly':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            default:
                break;
        }

        $salesData = $query->get();
        $totalAmount = $salesData->sum('total_price');
        return response()->json([
            'salesData' => $salesData,
            'totalAmount' => $totalAmount,
        ]);
    }

    // public function getVendorsByDepartment($vendorId)
    // {
    //     $vendors = AssignVendorToDepartment::where('department_id', $vendorId)
    //         ->join('vendors', 'assign_vendor_to_departments.vendor_id', '=', 'vendors.id')
    //         ->select('vendors.id', 'vendors.vendor_name')
    //         ->get();

    //     return response()->json([
    //         'vendors' => $vendors
    //     ]);
    // }
}

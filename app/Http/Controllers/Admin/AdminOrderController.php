<?php

namespace App\Http\Controllers\Admin;

use App\Models\Orders;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function getOrderCount()
    {
        $orderCount = Orders::where('status', 'in-Progress')->count();
        return response()->json(['count' => $orderCount]);
    }

    public function index()
    {
        $orders = Orders::with('manager', 'store', 'vendor', 'orderitem')
        ->whereIn('status', ['In-progress', 'Completed'])
        ->orderByRaw("FIELD(status, 'In-progress', 'Completed')") // 'In-progress' first, then 'Completed'
        ->orderBy('created_at', 'desc') // Order by most recent orders within each status
        ->get();
        // return $orders;
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        // return $request;
        $order = Orders::findOrFail($request->order_id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('message', 'Order Status Updated Successfully');
    }

    public function orderDetail($id)
    {
        // return $request;
        $orderItems = OrderItem::where('order_id', $id)->with('product')->latest()->get();
        // return $orderItems;
        return view('admin.ordersItems.index', compact('orderItems'));
    }
}

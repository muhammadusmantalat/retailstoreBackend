<?php

namespace App\Http\Controllers\Managers;

use App\Models\Audit;
use App\Models\Orders;
use App\Mail\AuditReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\checkOrder;
use Illuminate\Support\Facades\Mail;

class AuditController extends Controller
{
    public function initiateAudit(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'overcharged_prices' => 'nullable',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $order = Orders::findOrFail($request->order_id);
            $vendor = $order->vendor;

            // Check receipts
            $receipt = checkOrder::where('order_id', $request->order_id)->first();
            $storeManagerReceipt = $receipt->manager_recepit ?? null;
            // dd( $storeManagerReceipt);
            $vendorReceipt = $receipt->image ?? null;

            // return $vendorReceipt;

            // Update or create the audit record
            $audit = Audit::updateOrCreate(
                ['order_id' => $order->id, 'vendor_id' => $request->vendor_id],
                ['overcharged_prices' => $request->overcharged_prices ?? 0, 'description' => $request->description]
            );

            // Send email if overcharged prices is checked
            if ($request->has('overcharged_prices') && $request->overcharged_prices == 1) {
                $vendor->overcharged_prices = 1; // Assuming 'overcharged' is the column name in the vendors table
                $vendor->save();
                Mail::to($vendor->email)->send(new AuditReport(
                    $order,
                    $order->order_code,
                    $request->description,
                    $order->store_manager_name,
                    $order->store_name,
                    $order->store_address,
                    $order->store_phone_no,
                    $vendor->vendor_name,
                    $vendorReceipt,
                    $storeManagerReceipt
                ));
            }

            return response()->json(['message' => 'Audit initiated and email sent to the vendor.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while initiating the audit.'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Managers;

use Exception;

use App\Models\Audit;


use App\Models\Vendor;
use App\Models\Product;
use App\Models\Department;
use App\Models\AssignVendor;
use App\Models\Notification;
use App\Models\StoreVendorGenralDiscount;
use App\Models\StoreHasSalesManager;
use Illuminate\Http\Request;
use App\Models\ProductAssign;
use App\Models\NotificationDay;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Helpers\Notificationhelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\ProductAssignToVendor;
use App\Models\AssignVendorToDepartment;
use App\Models\ProductAssignToDepartment;
use Illuminate\Support\Facades\Validator;
use App\Models\StoreManagerStoreDepartment;

class AddVendorToStoreManager extends Controller
{
    public function index()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        // return $storeId;
        // $vendors = Vendor::with('assignVendors')->where('store_id', $StoreId->store_id)->orderBy('id', 'DESC')->get();
        $vendors = AssignVendor::with([
            'vendor.salesMen' => function ($q) use ($StoreId, $authId) {
                $q->where('store_id', $StoreId->store_id);
                $q->where('store_manager_id', $authId);
            },
            'vendor.discount' => function ($q) use ($StoreId, $authId) {
                $q->where('store_id', $StoreId->store_id);
                $q->where('store_manager_id', $authId);
            }
        ])
        ->where('store_id', $StoreId->store_id)
        ->has('vendor')
        ->orderBy('id', 'DESC')
        ->get();

        // return $vendors;
        $frequencies = [
            1 => 'Every week',
            2 => 'After 2 weeks',
            3 => 'After 3 weeks',
            4 => 'After 4 weeks',
        ];
        // return $vendors;
        return view('managers.vendor.index', compact('vendors', 'frequencies'));
    }

    public function createVondor()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        // return $StoreId;
        return view('managers.vendor.create', compact('authId', 'StoreId'));
    }

    public function store(Request $request)
    {
        $authId = Auth::guard('web')->id();

        // Validate the request data
        $request->validate([
            'wholesaler_name' => 'required',
            'wholesaler_email' => 'required|email|max:255', // Ensure vendor email is required, unique, and a valid email format
            'wholesaler_phone_number' => 'required',
            'order_days' => 'required',
            'delivery_days' => 'required',
            // 'salesman_name' => 'required',
            // 'salesman_phone_number' => 'required',
            'sales_manager_name' => 'required|string|max:255',
            // 'sales_manager_email' => 'nullable|email|unique:store_has_sales_managers,sales_manager_email', 
            
            'sales_manager_phone_no' => 'required|string|max:20',
            'order_frequency' => 'required',
            'delivery_frequency' => 'required',
            'general_discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // return $request;

        // Prepare order_dates from dates array, if available
        // $order_days = $request->has('order_days') ? implode(',', $request->order_days) : null;
        // $delivery_days = $request->has('delivery_days') ? implode(',', $request->delivery_days) : null;

        $general_discount = $request->input('general_discount');
        $general_discount = $general_discount === null ? 0.00 : $general_discount;


        // $image = null;
        // if ($request->hasFile('vendor_image')) {
        //     $file = $request->file('vendor_image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // }

        // Create a new Vendor record
        $vendor = Vendor::where('email', $request->wholesaler_email)->first();
            // return $vendor;
        if($vendor) {
            // $vendor->email = $request->wholesaler_email;
            $vendor->vendor_name = $request->wholesaler_name;
            $vendor->phone_no = $request->wholesaler_phone_number;
            $vendor->save();
        }
        else {
            return 'no';

            $vendor = Vendor::create([
                'vendor_name' => $request->wholesaler_name,
                'email' => $request->wholesaler_email,
                'phone_no' => $request->wholesaler_phone_number,
            ]);
        }

        $general_discount_data = StoreVendorGenralDiscount::create([
            'store_manager_id' => $authId,
            'store_id' => $request->store_id,
            'vendor_id' => $vendor->id,
            'general_discount' => $general_discount
        ]);
         $salesManager = StoreHasSalesManager::create([
            'store_manager_id' => $authId,
            'store_id' => $request->store_id,
            'whole_seller_id' => $vendor->id,
            'sales_manager_name' => $request->sales_manager_name,
            'sales_manager_email' => $request->sales_manager_email,
            'sales_manager_phone_no' => $request->sales_manager_phone_no,
            'order_dates' => $request->order_days,
            'delivery_days' => $request->delivery_days,
            'order_frequency' => $request->order_frequency,
            'delivery_frequency' => $request->delivery_frequency,
        ]);

        $date =  Notificationhelper::deliveryNotification($salesManager->delivery_frequency, $salesManager->delivery_days);
        $notification = NotificationDay::create([
            'store_manager_id' => $request->store_manager_id,
            'store_id' => $request->store_id,
            'vendor_id' => $vendor->id,
            'notification_date' => $date,

        ]);

        // Ensure vendor was created successfully before proceeding
        if ($vendor) {
            // Create an AssignVendor record with the newly created vendor's ID
            AssignVendor::updateOrCreate(
            [
                'store_manager_id' => $request->store_manager_id,
                'store_id' => $request->store_id,
                'vendor_id' => $vendor->id
            ],
            [] // agar koi aur fields nahi update karni to empty rehne do
            );

            // Redirect with a success message
            return redirect()->route('manager.storeManagerVendor')->with(['status' => true, 'message' => 'Wholesaler Added Successfully']);
        }
    }


    public function editVendor($id)
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        $vendor = Vendor::with([
            'salesMen' => function ($q) use ($StoreId, $authId) {
                $q->where('store_id', $StoreId->store_id);
                $q->where('store_manager_id', $authId);
            },
            'discount' => function ($q) use ($StoreId, $authId) {
                $q->where('store_id', $StoreId->store_id);
                $q->where('store_manager_id', $authId);
            }
        ])->find($id);
        return view('managers.vendor.edit', compact('vendor'));
    }

    public function updateVendor(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        // return $request;
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        // $storeId = $StoreId->store_id;
        $salesManager = StoreHasSalesManager::where('whole_seller_id', $vendor->id)->where('store_id', $StoreId->store_id)->first();
        $request->validate([
            'wholesaler_name' => 'required',
            'wholesaler_email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($vendor->id), // Ignore the current user's email
                'max:255'
            ],
            'wholesaler_phone_number' => 'required|regex:/^\+1 \d{3} \d{3} \d{4}$/',
            'order_days' => 'required',
            'delivery_days' => 'required',
            'sales_manager_name' => 'required|string|max:255',
            'sales_manager_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('store_has_sales_managers', 'sales_manager_email')
                    ->where(function ($query) use ($StoreId, $vendor,$authId) {
                        return $query->where('store_id', $StoreId->store_id)
                                    ->where('whole_seller_id', $vendor->id)
                                    ->where('store_manager_id', $authId);
                    })
                    ->ignore($salesManager->id ?? null) // for update case
            ],
            'sales_manager_phone_no' => 'required|string|max:20',
            'order_frequency' => 'required',
            'delivery_frequency' => 'required',
            'general_discount' => 'nullable|numeric|min:0|max:100',
            'overcharged' => 'required'
        ]);

        // return $request;


        // $image = $vendors->image;

        // if ($request->hasFile('vendor_image')) {
        //     $destination = 'public/admin/assets/images/users/' . $vendors->image;
        //     if (File::exists($destination)) {
        //         File::delete($destination);
        //     }

        //     $file = $request->file('vendor_image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // }

        // $order_days = $request->has('order_days') ? implode(',', $request->order_days) : null;
        // $delivery_days = $request->has('delivery_days') ? implode(',', $request->delivery_days) : null;

        $currentOverchargedStatus = $vendor->overcharged_prices;

        $vendor->update([
            'vendor_name' => $request->wholesaler_name,
            'email' => $request->wholesaler_email,
            'phone_no' => $request->wholesaler_phone_number,
            'overcharged_prices' => $request->overcharged,
            'over_charged_by' => $request->overcharged ? $authId : null
        ]);
        $general_discount = StoreVendorGenralDiscount::updateOrCreate(
            [
                'store_id'  => $StoreId->store_id,
                'vendor_id' => $vendor->id,
                'store_manager_id' => $authId,
            ],
            [
                'general_discount' => $request->general_discount,
            ]
        );
        $salesManager = StoreHasSalesManager::updateOrCreate(
        [
            'store_id'        => $StoreId->store_id,
            'whole_seller_id' => $vendor->id,
            'store_manager_id' => $authId,
        ],
        [
            'sales_manager_name'     => $request->sales_manager_name,
            'sales_manager_email'    => $request->sales_manager_email,
            'sales_manager_phone_no' => $request->sales_manager_phone_no,
            'order_dates'            => $request->order_days,
            'delivery_days'          => $request->delivery_days,
            'order_frequency'        => $request->order_frequency,
            'delivery_frequency'     => $request->delivery_frequency,
        ]
        );
        $date = Notificationhelper::deliveryNotification($salesManager->delivery_frequency, $salesManager->delivery_days);
        $notification = NotificationDay::firstOrCreate(
            [
                'store_manager_id' => $authId,
                'store_id' => $StoreId->store_id,
                'vendor_id' => $vendor->id,
            ],
            [
                'notification_date' => $date,
            ]
        );

        $notification->update([
            'notification_date' => $date, // This will ensure the date is always updated
        ]);

        Audit::where('vendor_id', $vendor->id)
            ->update([
                'overcharged_prices' => $request->overcharged,
                'description' => null, // Set description to null or any specific value if needed
                'updated_at' => now(),
            ]);

        // return $request ;
        return redirect()->route('manager.storeManagerVendor')->with(['status' => true, 'message' => 'Wholesaler Updated Successfully']);
    }

    public function deleteVendor($id)
    {
        Vendor::destroy($id);
        return redirect()->route('manager.storeManagerVendor')->with(['status' => true, 'message' => 'Wholesaler Deleted Successfully']);
    }

    public function toggleOverchargedPrices(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->overcharged_prices = !$vendor->overcharged_prices;
        $vendor->save();
        return redirect()->route('manager.storeManagerVendor')->with(['status' => true, 'message' => 'Overcharged Prices status updated successfully']);
    }

}

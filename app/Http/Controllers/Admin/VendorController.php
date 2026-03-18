<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\StoreManagerStoreDepartment;



class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('id', 'DESC')->get();

        $frequencies = [
            1 => 'Every week',
            2 => 'After 2 weeks',
            3 => 'After 3 weeks',
            4 => 'After 4 weeks',
        ];
        // return $vendors;
        return view('admin.vendor.index', compact('vendors','frequencies'));
    }

    public function create()
    {
        return view('admin.vendor.create');
    }

    public function save(Request $request)
    {
        // return $request;
        $request->validate([
            'wholesaler_name' => 'required',
            'wholesaler_email' => 'nullable|email|unique:vendors,email|max:255', // Use the unique rule directly
            'wholesaler_phone_number' => 'required',
            // 'order_days' => 'required',
            // 'delivery_days' => 'required',
            // 'salesman_name' => 'required',
            // 'salesman_phone_number' => 'required',
            // 'order_frequency' => 'required',
            // 'delivery_frequency' => 'required',
            // 'general_discount' => 'nullable|numeric|min:0|max:100',

        ]);
        // $order_days = $request->has('order_days') ? implode(',', $request->order_days) : null;
        // $delivery_days = $request->has('delivery_days') ? implode(',', $request->delivery_days) : null;
        // return $request;

        // $general_discount = $request->input('general_discount', 0.00);

        // $general_discount = $request->input('general_discount');
        // $general_discount = $general_discount === null ? 0.00 : $general_discount;


        // $image = null;

        // if ($request->hasFile('vendor_image')) {
        //     $file = $request->file('vendor_image');
        //     $extension = $file->getClientOriginalExtension();
        //     $filename = time() . '.' . $extension;
        //     $file->move(public_path('admin/assets/images/users/'), $filename);
        //     $image = 'public/admin/assets/images/users/' . $filename;
        // }

        $vendors = Vendor::create([

            'vendor_name' => $request->wholesaler_name,
            'email' => $request->wholesaler_email,
            'phone_no' => $request->wholesaler_phone_number,
            // 'order_dates' => $request->order_days,
            // 'delivery_days' => $request->delivery_days,
            // 'salesman_name' => $request->salesman_name,
            // 'salesman_phone_no' => $request->salesman_phone_number,
            // 'order_frequency' => $request->order_frequency,
            // 'delivery_frequency' => $request->delivery_frequency,
            // 'general_discount' => $general_discount,
            // 'image' =>  $image
        ]);
        return redirect()->route('vendors')->with(['status' => true, 'message' => 'Wholesaler Added Successfully']);
    }

    public function edit($id)
    {
        $vendors = Vendor::find($id);
        // return $vendors;
        return view('admin.vendor.edit', compact('vendors'));
    }

    public function update(Request $request, $id)

    {
        $vendors = Vendor::find($id);
        // return $request;
        $request->validate([
            // 'storeManager_id' => 'required',
            'Wholesaler_name' => 'required',
            'wholesaler_email' => [
                'nullable',
                'email',
                Rule::unique('vendors', 'email')->ignore($vendors->id), // Ignore the current user's email
                'max:255'
            ],
            'wholesaler_phone_number' => 'required|regex:/^\+1 \d{3} \d{3} \d{4}$/',
            // 'order_days' => 'required',
            // 'delivery_days' => 'required',
            // 'salesman_name' => 'required',
            // 'salesman_phone_number' => 'required|regex:/^\+1 \d{3} \d{3} \d{4}$/',
            // 'order_frequency' => 'required',
            // 'delivery_frequency' => 'required',
            // 'general_discount' => 'required'
        ]);



        $image = $vendors->image;

        if ($request->hasFile('vendor_image')) {
            $destination = 'public/admin/assets/images/users/' . $vendors->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('vendor_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        }

        // $order_days = $request->has('order_days') ? implode(',', $request->order_days) : null;
        // $delivery_days = $request->has('delivery_days') ? implode(',', $request->delivery_days) : null;

        $vendors->update([
            // 'store_manager_id' => $request->storeManager_id
            'vendor_name' => $request->Wholesaler_name,
            'email' => $request->wholesaler_email,
            'phone_no' => $request->wholesaler_phone_number,
            // 'order_dates' => $request->order_days,
            // 'delivery_days' => $request->delivery_days,
            // 'salesman_name' => $request->salesman_name,
            // 'salesman_phone_no' => $request->salesman_phone_number,
            // 'order_frequency' => $request->order_frequency,
            // 'delivery_frequency' => $request->delivery_frequency,
            // 'general_discount' => $request->general_discount,
            // 'image' => $image,

        ]);

        return redirect()->route('vendors')->with(['status' => true, 'message' => 'Wholesaler Updated Successfully']);
    }

    public function destroy($id)
    {
        Vendor::destroy($id);
        return redirect()->route('vendors')->with(['status' => true, 'message' => 'Wholesaler Deleted Successfully']);
    }
}

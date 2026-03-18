<?php

namespace App\Http\Controllers\Managers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreHasSalesManager;
use App\Models\StoreManagerStoreDepartment;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class StoreSaleManagerController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('web')->id();
        $storeManagerDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $saleManager = StoreHasSalesManager::where('store_id', $storeManagerDepartment->store_id)->get();
        return view('managers.storeSaleManager.index', compact('saleManager', 'storeManagerDepartment'));
    }
    public function create()
    {

        $authId = Auth::guard('web')->id();
        $storeManagerDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        return view('managers.storeSaleManager.create');
    }

    public function store(Request $request)
    {
        $authId = Auth::guard('web')->id();
        $storeManagerDepartment = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();  
        $validatedData = $request->validate([
            'sales_manager_name' => 'required|string|max:255',
            'sales_manager_email' => 'nullable|email|unique:store_has_sales_managers,sales_manager_email',
            'sales_manager_phone_no' => 'required|string|max:20',
        ]);
        // Create the department with or without an image
        StoreHasSalesManager::create([
            'sales_manager_name' => $validatedData['sales_manager_name'],
            'sales_manager_email' => $validatedData['sales_manager_email'],
            'sales_manager_phone_no' => $validatedData['sales_manager_phone_no'],
            'store_id' => $storeManagerDepartment->store_id,
        ]);
        
        return redirect()->route('manager.storeSaleManager.index')->with(['status' => true, 'message' => 'Salesmen Added Successfully']);
    }



    public function edit($id)
    {
        // return $id;
        $data = StoreHasSalesManager::find($id);
        // return  $departments;
        return view('managers.storeSaleManager.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // return $request;
        $request->validate([
            'sales_manager_name' => 'required|string|max:255',
            'sales_manager_email' => [
                'nullable',
                'email',
                Rule::unique('store_has_sales_managers', 'sales_manager_email')->ignore($id),
                'max:255'
            ],
            'sales_manager_phone_no' => 'required|string|max:20',
        ]);
        $data = StoreHasSalesManager::find($id);

        $data->update([
            'sales_manager_name' => $request->sales_manager_name,
            'sales_manager_email' => $request->sales_manager_email,
            'sales_manager_phone_no' => $request->sales_manager_phone_no
        ]);
        return redirect()->route('manager.storeSaleManager.index')->with(['status' => true, 'message' => 'Salesmen Updated Successfully']);
    }
}

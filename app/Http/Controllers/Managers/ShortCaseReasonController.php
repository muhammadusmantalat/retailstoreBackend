<?php

namespace App\Http\Controllers\managers;

use Illuminate\Http\Request;
use App\Models\ShortCaseReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreManagerStoreDepartment;

class ShortCaseReasonController extends Controller
{
    public function index()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;

        $reasons = ShortCaseReason::where('store_manager_id', $authId)
        ->where('store_id', $storeId)
        ->latest()
        ->get();
return view('managers.shortCase.index', compact('reasons', 'authId','storeId'));
    }

    public function create()
    {
        $authId = Auth::guard('web')->id();
        $StoreId = StoreManagerStoreDepartment::where('store_manager_id', $authId)->first();
        $storeId = $StoreId->store_id;
        return view('managers.shortCase.create', compact('authId','storeId'));
    }

    public function store(Request $request)
    {
        // return $request;
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the short case reason already exists
        $existingReason = ShortCaseReason::where('reason', $request->name)->first();

        if ($existingReason) {
            // Redirect back with an error message
            return redirect()->back()
            ->with(['status' => false, 'message' => 'This reason already exists']);
        }

        // Create a new short case reason
        ShortCaseReason::create([
            'reason' => $request->name,
            'store_manager_id' => $request->store_manager_id,
            'store_id' => $request->store_id,
        ]);

        // Redirect with a success message
        return redirect()->route('manager.shortCase')
            ->with(['status' => true, 'message' => 'Short case reason saved successfully']);
    }


    public function edit($id)
    {
        $reason = ShortCaseReason::find($id);
        return view('managers.shortCase.edit', compact('reason'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if the short case reason with the same name exists, excluding the current record
        $existingReason = ShortCaseReason::where('reason', $request->name)
            ->where('id', '!=', $id) // Exclude the current record from the check
            ->first();

        if ($existingReason) {
            // Redirect back with an error message
            return redirect()->back()
            ->with(['status' => false, 'message' => 'This reason already exists']);

        }

        // Find the current short case reason and update it
        $shortCase = ShortCaseReason::findOrFail($id);
        $shortCase->update([
            'reason' => $request->name
        ]);

        // Redirect with a success message
        return redirect()->route('manager.shortCase')
            ->with(['status' => true, 'message' => 'Short case reason updated successfully']);
    }


    public function destroy($id)
    {
        ShortCaseReason::destroy($id);
        return redirect()->route('manager.shortCase')
            ->with(['status' => true, 'message' => 'Short case reason deleted successfully']);
    }
}

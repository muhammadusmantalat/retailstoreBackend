<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAssign;
use App\Http\Controllers\Controller;

class AssignProductController extends Controller
{
    public function index($id)
    {
        $productsAssigned = ProductAssign::with('storeManager', 'store')->where('product_id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.productAssign.index', compact('productsAssigned', 'id'));
    }

    public function create($id)
    {
        $storeManagers = User::all();
        $stores = Store::all();
        $product = Product::find($id);

        return view('admin.productAssign.create', compact('storeManagers', 'stores', 'product', 'id'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'storeManager' => 'required',
            'store' => 'required|array',
            'store.*' => 'required',
            'product_id' => 'required',
        ]);


        $storeManagerId = $request->input('storeManager');
        $storeIds = $request->input('store');
        $productId = $request->input('product_id');

        foreach ($storeIds as $storeId) {
            ProductAssign::firstOrCreate([
                'store_manager_id' => $storeManagerId,
                'store_id' => $storeId,
                'product_id' => $productId,
            ]);
        }
        return redirect()->route('assignProducts', ['id' => $request->product_id])->with(['status' => true, 'message' => 'Store Manager & Store Assigned Successfully']);
    }

    public function edit($storeManagerId, $id)
    {
        $assignments = ProductAssign::where('store_manager_id', $storeManagerId)->where('product_id' , $id)->get();
        $stores = Store::where('storeManger_id', $storeManagerId)->get();

        return view('admin.productAssign.edit', compact('stores', 'assignments', 'storeManagerId', 'id'));
    }

    public function update(Request $request, $storeManagerId)
    {

        $assignmentsData = $request->input('assignments', []);
        foreach ($assignmentsData as $assignmentId => $assignmentData) {
            $assignment = ProductAssign::findOrFail($assignmentId);
            $assignment->update([
                'store_id' => $assignmentData['store_id'],
            ]);
        }
        return redirect()->route('assignProducts', ['id' => $request->product_id])->with(['status' => true, 'message' => 'Store Manager & Store Updated Successfully']);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $result = ProductAssign::where('id', $id)->delete();
        if ($result) {
            return response()->json(['success' => 'Assignment deleted successfully!']);
        } else {
            return response()->json(['error' => 'Error deleting assignment.'], 404);
        }
    }





    public function getProductStores(Request $request, $id)
    {
        try {
            $data = Store::where("storeManger_id", $id)->get();
            return response()->json([
                'data' => $data,
                'status' => 'Success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'No StoreId Found ' . $e->getMessage(),
                'status' => 'Error'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HotSaleProduct;
use Illuminate\Http\Request;

class hotSaleProductController extends Controller
{
    public function index()
    {
        $hotSaleProducts = HotSaleProduct::orderBy('id', 'DESC')->get();
        return view('admin.hotSellingProducts.index', compact('hotSaleProducts'));
    }

    public function create()
    {
        return view('admin.hotSellingProducts.create');
    }
    public function save(Request $request)
    {
        //  return $request;
        $request->validate([
            'quantity' => 'required|integer|min:1', // Validate that quantity is required, an integer, and at least 1
        ]);

        HotSaleProduct::create([
            'quantity' => $request->quantity,

        ]);

        return redirect()->route('hotSalingProduct.index')->with(['status' => true, 'message' => 'Quantity added successfully']);
    }

    public function edit($id)
    {
        $product = HotSaleProduct::find($id);
        return view('admin.hotSellingProducts.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        //  return $request;
        $request->validate([
            'quantity' => 'required|integer|min:1', // Validate that quantity is required, an integer, and at least 1
        ]);
        $product = HotSaleProduct::find($id);


        $product->update([
            'quantity' => $request->quantity,

        ]);

        return redirect()->route('hotSalingProduct.index')->with(['status' => true, 'message' => 'Quantity updated successfully']);
    }

    public function destroy($id)
    {
        HotSaleProduct::destroy($id);
        return redirect('hotSalingProduct.index')->back()->with(['status' => true, 'message' => 'Quantity deleted successfully']);
    }
}

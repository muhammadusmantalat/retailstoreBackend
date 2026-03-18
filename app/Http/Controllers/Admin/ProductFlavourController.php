<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductFlavour;

class ProductFlavourController extends Controller
{

    public function index($id)
    {
        $products = ProductFlavour::where('product_id', $id)->get();
        // return $products;
        return view('admin.productFlavour.index', compact('id', 'products'));
    }

    public function create($id)
    {
        $product = Product::where('id', $id)->first();
        // return $product;

        return view('admin.productFlavour.create', compact('product'));
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'product_id' => 'required',
            'flavour_name' => 'required|array',
            'flavour_name.*' => 'required',

        ]);

        $flavourNames = $request->input('flavour_name');

        foreach ($flavourNames as $flavourName) {
            ProductFlavour::firstOrCreate([
                'product_id' => $request->product_id,
                'flavour_name' => $flavourName,

            ]);
        }
        return redirect()->route('products-flavours', ['id' => $request->product_id])
            ->with(['status' => true, 'message' => 'Flavor / Variant Assigned Successfully']);
    }

    public function edit($productId, $flavourId)
    {
        // return $id;

        $productFlavour = ProductFlavour::find($flavourId);
        // return $productFlavour;
        return view('admin.productFlavour.edit', compact('productFlavour', 'productId'));
    }

    public function update(Request $request, $flavourId)
    {
        $request->validate([
            'flavour_name' => 'required'
        ]);

        // Check if the new flavour name already exists (other than the current one being updated)
        $existingFlavour = ProductFlavour::where('flavour_name', $request->flavour_name)
            ->where('id', '<>', $flavourId)
            ->first();

        // If a different flavour with the same name exists, delete it
        if ($existingFlavour) {
            $existingFlavour->delete();
        }

        // Find the flavour that needs to be updated
        $productFlavour = ProductFlavour::find($flavourId);
        if (!$productFlavour) {
            return redirect()->back()->with(['status' => false, 'message' => 'Product Flavor Not Found']);
        }

        // Update the flavour with the new name
        $productFlavour->flavour_name = $request->flavour_name;
        $productFlavour->save();

        // Redirect to the product flavour page with a success message
        return redirect()->route('products-flavours', ['id' => $request->product_id])
            ->with(['status' => true, 'message' => 'Flavor / Variant Updated Successfully']);
    }

    public function destroy($productId, $flavourId)
    {
        // return $productId;
        ProductFlavour::destroy($flavourId);
        return redirect()->route('products-flavours', ['id' => $productId])->with(['status' => true, 'message' => 'Flavor / Variant Updated Successfully']);
    }
}

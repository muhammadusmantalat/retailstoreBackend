<?php

namespace App\Http\Controllers\managers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductFlavour;
use App\Http\Controllers\Controller;
use App\Models\ProductAssign;

class ProductFlavorController extends Controller
{
    public function index($id)
    {

        $products = ProductFlavour::where('product_id', $id)->get();
        // return $products;
        return view('managers.productFlavor.index', compact('id', 'products'));
    }

    public function create($id)
    {
        $product = Product::where('id', $id)->first();
        // return $product;

        return view('managers.productFlavor.create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'flavour_name' => 'array|required',
            'flavour_name.*' => 'required',
            'product_id' => 'required',
        ]);

        $flavourNames = $request->input('flavour_name');

        foreach ($flavourNames as $flavourName) {
            ProductFlavour::firstorCreate([
                'product_id' => $request->product_id,
                'flavour_name' => $flavourName,

            ]);
        }

        return redirect()->route('manager.productFlavor', ['id' => $request->product_id])
            ->with(['status' => true, 'message' => 'Flavor / Variant Assigned Successfully']);
    }

    public function edit($productId, $flavourId)
    {
        $productFlavour = ProductFlavour::find($flavourId);
        // return $productFlavour;
        return view('managers.productFlavor.edit', compact('productFlavour', 'productId'));
    }

    // public function update(Request $request, $flavourId)
    // {
    //     $request->validate([

    //         'flavour_name' => 'required'

    //     ]);
    //     $existingFlavour = ProductFlavour::where('flavour_name', $request->flavour_name)->first();

    //     if ($existingFlavour){
    //         $existingFlavour->delete();
    //     }

    //     $productFlavour = ProductFlavour::find($flavourId);
    //     if ($productFlavour)
    //     {
    //         $productFlavour->firstorCreate([
    //             'flavour_name' => $request->flavour_name,
    //         ]);
    //         return redirect()->route('manager.productFlavor', ['id' => $request->product_id])->with(['status' => true, 'message' => 'Product Flavour Updated Successfully']);
    //         return $request;
    //     }
    // }

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
            return redirect()->back()->with(['status' => false, 'message' => 'Product Flavour Not Found']);
        }

        // Update the flavour with the new name
        $productFlavour->flavour_name = $request->flavour_name;
        $productFlavour->save();

        // Redirect to the product flavour page with a success message
        return redirect()->route('manager.productFlavor', ['id' => $request->product_id])
            ->with(['status' => true, 'message' => 'Flavor / Variant Updated Successfully']);
    }

    public function destroy($productId, $flavourId)
    {
        // return $productId;
        ProductFlavour::destroy($flavourId);
        return redirect()->route('manager.productFlavor', ['id' => $productId])->with(['status' => true, 'message' => 'Flavor / Variant Updated Successfully']);
    }

  
}

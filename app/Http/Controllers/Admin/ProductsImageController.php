<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductsPhoto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProductsImageController extends Controller
{
    public function index($id)
    {
        $productImages = ProductsPhoto::where('product_id', $id)->get();
        return view('admin.productImages.index', compact('productImages', 'id'));
    }


    public function create($id)
    {
        $product = Product::where('id', $id)->first();
        return view('admin.productImages.create', compact('product'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'images' => 'required|array|min:1|max:5', // Ensure at least one and no more than five images
        //     'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' // Validate each image file type and size
        // ], [
        //     'images.required' => 'You must upload at least one image.',
        //     'images.min' => 'You must upload at least one image.',
        //     'images.max' => 'You can only upload a maximum of 5 images.',
        //     'images.*.image' => 'Each file must be an image.',
        //     'images.*.mimes' => 'Each image must be a file of type: jpeg, png, jpg, gif, svg.',
        //     'images.*.max' => 'Each image must not exceed 2MB.'
        // ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = time() . rand(1, 100) . '.' . $extension;
                $file->move(public_path('admin/assets/images/products/'), $filename);
                $imagePath = 'public/admin/assets/images/products/' . $filename;
                ProductsPhoto::create([
                    'product_id' => $request->product_id,
                    'product_image' => $imagePath
                ]);
            }
        }

        return redirect()->route('ProductsImages', ['id' => $request->product_id])->with(['status' => true, 'message' => 'Product Image Added Successfully']);

    }

    public function edit ($id)
    {
        $productImage = ProductsPhoto::find($id);

        return view('admin.productImages.edit', compact('productImage'));
    }

    public function update(Request $request, $id)
    {
        $productImage = ProductsPhoto::findOrFail($id);
        // return $productImage;

        if ($request->hasFile('image')) {
            $destination = $productImage->product_image;

            // Check if the file exists and delete it
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('admin/assets/images/products', $filename);

            // Update the image path
            $productImage->product_image = 'admin/assets/images/products/' . $filename;
            $productImage->save();
        }

        return redirect()->route('ProductsImages', ['id' => $productImage->product_id])->with(['status' => true, 'message' => 'Product Image Updated Successfully']);
    }

    public function destory($productId, $imageId)
    {
        // return $productId;
        ProductsPhoto::destroy($imageId);
        return redirect()->route('ProductsImages', ['id' => $productId])->with(['status' => true, 'message' => 'Product Image Deleted Successfully']);
    }
}

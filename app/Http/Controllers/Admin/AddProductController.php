<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductsPhoto;
use App\Models\ProductFlavour;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class AddProductController extends Controller
{
    public function index()
    {
        return view('admin.product.index');
    }

    public function getProducts(Request $request)
    {
        $query = Product::select(
                'id',
                'product_name',
                'price',
                'upc_ipc',
                'store_manager_id',
                'store_id'
            )
            ->with([
                'storeManager:id,first_name,last_name',
                'store:id,store_name'
            ]);

        // Search
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                ->orWhere('upc_ipc', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
            'pagination' => $products->links('pagination::bootstrap-4')->render(),
        ]);
    }
        public function create()
    {
        $storeManagers = User::where('user_type', 'store_Manager')->get();
        // return $storeManagers;
        $stores = Store::all();
        return view('admin.product.create', compact('storeManagers', 'stores',));
    }

    public function save(Request $request)
    {
        // return $request;
        $request->validate([
            'product_name' => 'required',
            'upc_ipc' => [
                'required',
                Rule::unique('products', 'upc_ipc')->where(function ($query) use ($request) {
                    return $query->where('store_id', $request->store_id);
                })->ignore($request->product_id, 'product_id'),
            ],
            // 'tax_status' => 'required',
            'store_manager_id' => 'required',
            'store_id' => 'required',
            'price' => 'required'

        ]);

        $product = Product::create([
            'store_manager_id' => $request->store_manager_id,
            'store_id' => $request->store_id,
            'product_name' => $request->product_name,
            'upc_ipc' => $request->upc_ipc,
            'price' => $request->price,
            // 'tax_status' => $request->tax_status,
        ]);

       // return $request;
        return redirect()->route('products')->with(['status' => true, 'message' => 'Product Added Successfully']);
    }

    public function show()
    {
    }


    public function edit($storeId, $id)
    {

        // return $storeId;
        $product = Product::find($id);
        $storeManagers = User::where('user_type', 'store_Manager')->get();

        return view('admin.product.edit', compact('product', 'storeManagers', 'storeId'));
    }

    public function update(Request $request, $id)
    {
        // return $request;

        $product = Product::find($id);

        $validatedData = $request->validate([
            'product_name' => 'required',
            'price' => 'required',

        ]);

        // return $request;

        $product->update([
            'product_name' => $validatedData['product_name'],
            'price' => $validatedData['price'],
        ]);
        // return $request;

        // return $request;
        return redirect()->route('products')->with(['status' => true, 'message' => 'Product Updated Successfully']);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('products')->with(['status' => true, 'message' => 'Product Deleted Successfully']);
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

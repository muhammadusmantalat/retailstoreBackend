<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


 class BannerController extends Controller
{
    public function index()
    {
        $banners = Banners::latest()->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        // return $request;
        $request->validate([
            'name' => 'required|array',
            'name.*' => 'required|string',
            'image' => 'required|array',
            'image.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $names = $request->name;
        $images = $request->file('image');

        foreach ($names as $index => $name) {
            $filename = '';
            if (isset($images[$index])) {
                $file = $images[$index];
                $extension = $file->getClientOriginalExtension();
                $filename = time() . rand(1, 100) . '.' . $extension;
                $file->move(public_path('admin/assets/images/products/'), $filename);
                $imagePath = 'public/admin/assets/images/products/' . $filename;
            }

            Banners::create([
                'name' => $name,
                'image' => $imagePath ?? null,
            ]);
        }

        return redirect()->route('banner')->with(['status' => true, 'message' => 'Banner Added Successfully']);
    }



    public function edit($id)
    {
        $banner = Banners::find($id);
        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $banner = Banners::find($id);

        if (!$banner) {
            return redirect()->route('banner')->with(['status' => false, 'message' => 'Banner not found']);
        }

        // Update banner name
        $banner->name = $request->name;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Validate and store new image
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/products'), $filename);

            // Delete old image if it exists
            if ($banner->image && File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
            }

            // Update the image path in the database
            $banner->image = 'public/admin/assets/images/products/' . $filename;
        }

        $banner->save();

        return redirect()->route('banner')->with(['status' => true, 'message' => 'Banner Updated Successfully']);
    }

    public function destroy($id)
    {
        Banners::destroy($id);
        return redirect()->route('banner')->with(['status' => true, 'message' => 'Banner Deleted Successfully']);
    }
}

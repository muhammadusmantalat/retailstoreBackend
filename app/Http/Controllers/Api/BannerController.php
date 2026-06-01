<?php

namespace App\Http\Controllers\Api;

use App\Models\Logo;
use App\Models\Banners;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{

    public function index()
    {
        $banners = Banners::select('name', 'image')->get();
        return response()->json([
            'status' => 'success',
            'message' => 'banners get successfully',
            'banners' => $banners
        ]);
    }
 
    public function logo()
    {
        $logo = Logo::all(); // Sare logos ko fetch karne ke liye
        return response()->json([
            'status' => 'success',
            'message' => 'logo get successfully',
            'banners' => $logo
        ]);
    }
}

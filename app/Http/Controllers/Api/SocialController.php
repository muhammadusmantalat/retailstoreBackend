<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialLink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SocialController extends Controller
{
    public function index()
    {
        // Fetch all social links from the database
        $socialLinks = SocialLink::all();

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Social links retrieved successfully',
            'social_links' => $socialLinks
        ]);
    }
}

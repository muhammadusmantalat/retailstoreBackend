<?php

namespace App\Http\Controllers\Admin;

use App\Models\RecommandBy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecommendedByController extends Controller


{
    public function index()
    {
        // Fetch recommendations with associated user and store
        $recommands = RecommandBy::with(['user', 'store'])->orderBy('id', 'DESC')->get();
        // return  $recommands;
        return view('admin.recommendedBy.index', compact('recommands'));
    }

    public function getRecommendedCount()
    {
        $recommendedCount = RecommandBy::where('status', 'active')->count();
        return response()->json(['count' => $recommendedCount]);
    }

    public function deactivateStatus(Request $request)
{
    try {
        $recommand = RecommandBy::find($request->id);

        if (!$recommand) {
            return response()->json(['success' => false, 'message' => 'Recommand not found.']);
        }

        // Update status to de-active
        $recommand->status = 'de-active';
        $recommand->save();

        return response()->json(['success' => true, 'message' => 'Status updated to de-active.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to update status.']);
    }
}

}

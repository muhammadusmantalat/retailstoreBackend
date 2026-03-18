<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function addPermission(Request $request)
    {
        // return $request;
        $validator = $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
        ]);
        $data = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        // return $data;
        if ($data) {
            return response()->json([
                'message' => 'permission added successfully.',
                'status' => 'success.',
                'data' => $data,
            ], 200);
        } else {
            return response()->json([
                'message' => 'permission not added.',
                'status' => 'failed.',
            ], 200);
        }
    }
    public function updatePermission(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'guard_name' => 'required',
        ]);
        $permission = Permission::find($id);
        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);
        if ($permission) {
            return response()->json([
                'message' => 'permission updated successfully.',
                'status' => 'success.',
                'data' => $permission,
            ], 200);
        } else {
            return response()->json([
                'message' => 'permission not added.',
                'status' => 'failed.',
            ], 200);
        }
    }
}

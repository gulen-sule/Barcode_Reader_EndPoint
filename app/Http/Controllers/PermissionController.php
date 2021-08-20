<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function setPermission(Request $request)
    {
        if ($request->user()->role != 3)
        return \response()->json(["message" => "You do not have Admin permission"]);

        $data = $request->validate([
            'user_id' => 'integer',
            'user_uuid' => 'string',
            'place_id' => 'string',
            'status' => 'string'
        ]);

        Permission::query()->create([
            'user_id' => $data['user_id'],
            'user_uuid' =>  $data['user_uuid'],
            'place_id' =>  $data['place_id'],
            'status' =>  $data['status'],
        ]);

        $response = ['message' => 'You have been successfully set permission!'];

        return response($response, 200);
    }
}

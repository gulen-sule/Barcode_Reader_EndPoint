<?php

namespace App\Http\Controllers;

use App\Models\Executive;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use function response;

class UserController extends Controller
{
    public function isPermitted(Request $request, $id_number)
    {
        if ($request->user()->role != 2)
            return response()->json(["message" => "You do not have executive's permission"]);

        $place_id = Executive::query()->where('user_id', \request()->user()->uuid)
            ->first()->value('place_id');

        // $place_uuid = Place::query()->where('id', $place_id)->get('uuid');
        if ($place_id == null)
            return response()->json(["message" => "Your do not have controller mission"]);

        $user_uuid = User::query()->where('id_number', $id_number)->value('uuid');


        if ($user_uuid == null)
            return response()->json(["message" => "There is none person in the list with this uuid"]);

        $permission = Permission::query()
            ->where('user_uuid', $user_uuid)
            ->where('place_id', $place_id)
            ->value('status');

        if ($permission != null) {
            if ($permission == true) {
                return User::query()->where('id_number', $id_number)->
                    first(['first_name', 'id_number', 'last_name', 'user_photo_path', 'role']) ;
            } else {
                return response()->json(["message" => "Your permission is denied"]);
            }
        } else return response()->json(["message" => "This place"]);

    }


}

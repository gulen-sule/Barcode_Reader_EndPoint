<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    function setPlaces(Request $request)
    {
        if ($request->user()->role != 3)
            return \response()->json(["message" => "You do not have Admin permission"]);

        $data = $request->validate([
            'name' => 'string',
        ]);
        Place::query()->create([
            'name'=>$data['name'],
            'uuid'=> Uuid::uuid()
        ]);
        $response = ['message' => 'You have been successfully set place!'];
        return response($response, 200);
    }
}

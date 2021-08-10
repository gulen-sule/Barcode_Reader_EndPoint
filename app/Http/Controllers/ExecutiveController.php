<?php

namespace App\Http\Controllers;

use App\Models\Executive;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;

class ExecutiveController extends Controller
{
    function setExecutive(Request $request){
        $data=$request->validate([
            'user_id'=>'string',
            'place_id'=>'string'
        ]);

        Executive::query()->create([
            'uuid'=>Uuid::uuid(),
            'user_id'=>$data['user_id'],
            'place_id'=>$data['place_id'],
        ]);

        $response = ['message' => 'You have been successfully set executive!'];

        return response($response, 200);
    }
}

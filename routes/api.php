<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return 2;sw
});*/

//$userModel=User::query(); neden global tanimlayamiyorum


Route::middleware('auth:sanctum')->get('/id/{id_number}', function ($id_number) {
    //  $userModel = new UserModel();
    /*return DB::table('user_data')->  //Query Builder ile yazilmis hali
    where('id_number', $id_number)->first();*/
    return User::query()->where('id_number', $id_number)->
    first(['first_name', 'id_number', 'last_name', 'user_photo_path']);
});

Route::get('/users', function ($id_number) {
    return User::all();//tum veriyi almak icin
});

Route::post('/tokens/create', function () {
    $user = new User();
    $token = $user->createToken($user->email);
    return ['token' => $token->plainTextToken];
});
Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/id/{id_number}', function (Request $request) {
    return auth()->user();
});




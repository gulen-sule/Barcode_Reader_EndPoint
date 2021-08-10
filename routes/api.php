<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlaceController;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return 2;sw
});*/

//$userModel=User::query(); neden global tanimlayamiyorum


Route::middleware('auth:sanctum')->post('/id/{id_number}', [UserController::class, 'isPermitted']);
Route::post('/setPermission', [PermissionController::class, 'setPermission']);
Route::post('/setPlace', [PlaceController::class, 'setPlaces']);
Route::post('/setExecutive', [ExecutiveController::class, 'setExecutive']);

Route::get('/users', function ($id_number) {
    return User::all();//tum veriyi almak icin
});

Route::post('/tokens/create', function () {
    $user = new User();
    $token = $user->createToken($user->email);
    return ['token' => $token->plainTextToken];
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->post('/me', [AuthController::class, 'me']);

/*Route::middleware('auth:sanctum')->get('/id/{id_number}', function (Request $request) {
    return auth()->user();
});*/





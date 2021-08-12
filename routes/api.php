<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlaceController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->post('/isPermitted', [UserController::class, 'isPermitted']);
Route::post('/setPermission', [PermissionController::class, 'setPermission']);
Route::post('/setPlace', [PlaceController::class, 'setPlaces']);
Route::post('/setExecutive', [ExecutiveController::class, 'setExecutive']);
Route::middleware('auth:sanctum')->
get('/getBarcode', [UserController::class, 'sendBarcodeToken']);

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

Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->get('/role', [AuthController::class, 'role']);






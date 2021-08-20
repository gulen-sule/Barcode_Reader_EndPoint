<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlaceController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/getBarcode', [UserController::class, 'sendBarcodeToken']);
    Route::post('/setPermission', [PermissionController::class, 'setPermission']);
    Route::post('/setPlace', [PlaceController::class, 'setPlaces']);
    Route::post('/setExecutive', [ExecutiveController::class, 'setExecutive']);
    Route::post('/isPermitted', [UserController::class, 'isPermitted']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/role', [AuthController::class, 'role']);

    Route::get('/users', function ($id_number) {
        return User::all();//tum veriyi almak icin
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);







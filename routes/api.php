<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return 2;
});

Route::get('/id/{id_number}', function ($id_number) {
    return DB::table('user_data')->
    where('id_number', $id_number)->first();
});


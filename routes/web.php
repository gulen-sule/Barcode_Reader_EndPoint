<?php

use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/all', function () {
    return \Illuminate\Support\Facades\DB::table('user_data')->get('*');
});
Route::get('/uuid/{uuid}', function ($uuid) {
    return \Illuminate\Support\Facades\DB::table('user_data')->where('uuid', $uuid)->get('*');

});
Route::prefix('/userdata')->group(function () {
    Route::get('/getdata', function () {
        $x = UserModel::all()->sortBy('first_name');
        foreach ($x as $key => $value) {
            echo $value['first_name'] . "<br/>";
        }
    });
    Route::get('/setdata', function () {
        //UserModel::query()->create()
        $model = new UserModel();
        $model->first_name = Faker\Provider\Person::firstNameMale();
        $model->last_name = "kaya";
        $model->user_photo_path = \Faker\Provider\Image::imageUrl();
        $model->id_number = 45523433027;
        $model->save();
    });

    Route::get('/updatedata', function () {
        UserModel::query()->where('first_name', 'Sule')->update(['last_name' => 'kosova']);
    });
    Route::get('/deletedata', function () {
        UserModel::query()->find(1)->delete();

    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/id/{id_number}', function ($id_number) {
    return DB::table('user_data')->
    where('id_number', $id_number)->first();
});

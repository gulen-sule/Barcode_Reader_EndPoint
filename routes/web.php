<?php

use App\Models\User;
use http\Client\Request;
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
    return \Illuminate\Support\Facades\DB::table('users')->get('*');
});
/*Route::get('/uuid/{uuid}', function ($uuid) {
    return \Illuminate\Support\Facades\DB::table('users')->where('uuid', $uuid)->get('*');

});*/
Route::prefix('/userdata')->group(function () {
    Route::get('/getdata', function () {
        $x = User::all()->sortBy('first_name');
        foreach ($x as $key => $value) {
            echo $value['first_name'] . "<br/>";
        }
    });
    Route::get('/setdata', function () {
        //UserModel::query()->create()
        $model = new User();
        $model->email = "maksimumm@gmail.com";
        $model->password = Faker\Provider\Uuid::uuid();
        $model->first_name = Faker\Provider\Person::firstNameFemale();
        $model->last_name = "Kurtuluş";
        $model->user_photo_path = \Faker\Provider\Image::imageUrl();
        $model->id_number = 423239862714;
        $model->save();
    });

    Route::get('/updatedata', function () {
        User::query()->where('first_name', 'Sule')->update(['last_name' => 'kosova']);
    });
    Route::get('/deletedata', function () {
        User::query()->find(1)->delete();

    });
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/id/{id_number}', function (Request $request) {
  return auth()->user();
});

/*
Route::middleware('auth:sanctum')->get('/id/{id_number}', function ($id_number) {
     $userModel = new UserModel();
    return DB::table('user_data')->  //Query Builder ile yazilmis hali
    where('id_number', $id_number)->first();
    return User::query()->where('id_number', $id_number)->
    first(['first_name', 'id_number', 'last_name', 'user_photo_path']);
}); */

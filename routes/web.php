<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeQueryController;
use App\Models\User;
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
});

Route::middleware('cors')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/getPlacesQueries/{places_id}', [BarcodeQueryController::class, 'getPlacesQueries']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/role', [AuthController::class, 'role']);
    Route::get('/getQueries', [BarcodeQueryController::class, 'getAdminPlaceQueries']);
});

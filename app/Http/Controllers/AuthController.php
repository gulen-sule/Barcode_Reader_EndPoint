<?php

namespace App\Http\Controllers;

use App\Models\BarcodeToken;
use App\Models\User;
use App\Traits\ApiResponser;
use Faker\Provider\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $attribute = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:11|min:11',
            'role' => 'required|integer',
            'photo_path' => 'string|nullable',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);
        $user = new User();
        $user::query()->create(
            ['uuid' => Uuid::uuid(),
                'first_name' => $attribute['first_name'],
                'id_number' => $attribute['id_number'],
                'user_photo_path' => $attribute['photo_path'],
                'role' => $attribute['role'],
                'last_name' => $attribute['last_name'],
                'password' => Hash::make($attribute['password']),
                'email' => $attribute['email']]);

        $user_id = $user::query()->where('email', $attribute['email'])->value('id');
        BarcodeToken::query()->create([
            'user_id' => $user_id,
            'barcode_token' => Uuid::uuid()
        ]);
        /*$user::query()->insert(['first_name' => $attribute['user_name'],
             'password' => Hash::make($attribute['password']),
             'email' => $attribute['email']]);// insert sadece bir tane giris yapilacaksa kullanilir
        */
        // $token = $user->createTokenable('auth_token', 'User', BigInteger::one())->plainTextToken;
        // tokenable_id nullable olmadiginda

        /*$token = $user->createToken('auth_token')->plainTextToken;
        $user::query()->where('email', $attribute['email'])->update(['remember_token' => $token]);
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);*/
        $response = ['message' => 'You have been successfully registered!'];

        return response($response, 200);
    }

    public function login(Request $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'), true)) {
            return \response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::query()->where('email', $request['email'])
            ->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                User::query()->where('email', \request()->user()->email)->update(['remember_token' => $token]);
                return \response()->json(['token' => $token]);
            } else {
                return \response()->json(["message" => "Password mismatch"]);
            }
        } else {
            return \response()->json(["message" => 'User does not exist']);
        }
    }


    public function me(Request $request)
    {
        return $request->user();
    }


    public function role(Request $request)
    {
        return $request->user()->role;
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }


    public function deleteUser(Request $request)
    {
        $user = $request->user();


    }


}

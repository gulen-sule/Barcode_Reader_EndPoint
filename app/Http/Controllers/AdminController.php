<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function setAdmin(Request $request): JsonResponse
    {
        csrf_token();

        $attribute = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:11|min:11',
            'photo_path' => 'string|nullable',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'place_uuid' => 'required|string',
        ]);

        $user_uuid = $this->setAdminUser($attribute);
        $place_uuid = $attribute['place_uuid'];
        Admin::query()->create([
            'uuid' => Uuid::uuid(),
            'user_uuid' => $user_uuid,
            'place_uuid' => $place_uuid
        ]);

        return response()->json(["token" => $user_uuid, "message" => 'You have been successfully registered as Admin!']);

    }

    private function setAdminUser(array $data): string
    {
        $uuid = Uuid::uuid();
        User::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'id_number' => $data['id_number'],
            'role' => 3,
            'photo_path' => $data['photo_path'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uuid' => $uuid
        ]);
        return $uuid;
    }
}

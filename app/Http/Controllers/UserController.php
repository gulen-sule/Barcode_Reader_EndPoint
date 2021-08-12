<?php

namespace App\Http\Controllers;

use App\Models\BarcodeToken;
use App\Models\Executive;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function response;

class UserController extends Controller
{
    public function isPermitted(Request $request)
    {
        $attribute = $request->validate([
            'token' => 'required|string|max:40',
        ]);
        $user_id=BarcodeToken::query()->where('barcode_token',$attribute['token'])
            ->value('user_id');
        if ($request->user()->role != 2)
            return response()->json(["message" => "You do not have executive's permission"]);

        $place_id = Executive::query()->where('user_id', \request()->user()->uuid)
            ->first()->value('place_id');

        if ($place_id == null)
            return response()->json(["message" => "Your do not have controller mission"]);

        $user_uuid = User::query()->where('id', $user_id)->value('uuid');

        if ($user_uuid == null)
            return response()->json(["message" => "There is none person in the list with this uuid"]);

        $permission = Permission::query()
            ->where('user_uuid', $user_uuid)
            ->where('place_id', $place_id)
            ->value('status');

        if ($permission != null) {
            if ($permission == true) {
                return User::query()->where('id', $user_id)->
                first(['first_name', 'id_number', 'last_name', 'user_photo_path', 'role']);
            } else {
                return response()->json(["message" => "Your permission is denied"]);
            }
        } else return response()->json(["message" => "This place"]);

    }

    public function sendBarcodeToken(Request $request): JsonResponse
    {
        $exp = 20;
        $barcode_token = Str::random(30);
        $user_id = $request->user()->id;
        BarcodeToken::query()->where('user_id', $user_id)->
        update(['barcode_token' => $barcode_token, 'expire_time' => \time() + 30]);

        return response()->json(['token' => $barcode_token, 'time' => $exp]);
    }


    private function createJwt(int $exp, string $token): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['exp' => $exp, 'token' => $token]);
        $base64UrlHeader = $this->getBase64($header);
        $base64UrlPayload = $this->getBase64($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." .
            $base64UrlPayload, 'abC123!', true);
        $base64UrlSignature = $this->getBase64($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    private function getBase64(string $str): array|string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($str));
    }
}

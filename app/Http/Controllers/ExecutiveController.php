<?php

namespace App\Http\Controllers;

use App\Models\BarcodeQuery;
use App\Models\BarcodeToken;
use App\Models\Executive;
use App\Models\Permission;
use App\Models\User;
use Faker\Provider\Uuid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExecutiveController extends Controller
{
    function setExecutive(Request $request)
    {
        if ($request->user()->role != 3)
            return \response()->json(["message" => "You do not have Admin permission"]);

        $data = $request->validate([
            'user_id' => 'string',
            'place_id' => 'string'
        ]);

        Executive::query()->create([
            'uuid' => Uuid::uuid(),
            'user_id' => $data['user_id'],
            'place_id' => $data['place_id'],
        ]);

        $response = ['message' => 'You have been successfully set executive!'];

        return response($response, 200);
    }

    function getUserFromQueryToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            'query_token' => 'string',
        ]);
        if ($request->user()->role != 2)
            return $this->responseDeniedMessage("You do not have controller mission", 3);

        $controller_uuid = $request->user()->uuid;

        $query = BarcodeQuery::query()->where(['controller_uuid' => $controller_uuid, 'uuid' => $data['query_token']]);
        if (time() > $query->value('expire_time'))

            return $this->responseDeniedMessage("This barcode is expired", 2);
        $user_uuid = $query->value('user_uuid');
        $user = User::query()->where('uuid', $user_uuid);

        return response()->json(["message" => "", "status" => 0,
            "first_name" => $user->value('first_name'),
            "id_number" => $user->value('id_number'),
            "last_name" => $user->value('last_name'),
            "user_photo_path" => $user->value('user_photo_path'),
            "role" => $user->value('role')]);

    }

    public function isPermitted(Request $request): JsonResponse
    {
        $attribute = $request->validate([
            'token' => 'required|string|max:40',
        ]);
        $token = BarcodeToken::query()->where('barcode_token', $attribute['token']);

        $user_id = $token->value('user_id');

        if ($user_id == null)
            return $this->responseDeniedMessage("This token is not valid", 7);

        if ($request->user()->role != 2) {
            $this->addToQueryHistory(1, null, $request->user()->uuid, null);
            return $this->responseDeniedMessage("You do not have controller mission", 3);
        }

        $exp_time = $token->value('expire_time');
        if (time() > $exp_time)
            return $this->responseDeniedMessage("This barcode is expired", 2);

        $place_id = Executive::query()->where('user_id', \request()->user()->uuid)
            ->first()->value('place_id');

        if ($place_id == null) {
            $this->addToQueryHistory(1, null, $request->user()->uuid, null);
            return $this->responseDeniedMessage("You do not have controller mission", 3);
        }

        $user_uuid = User::query()->where('id', $user_id)->value('uuid');

        if ($user_uuid == null)
            return $this->responseDeniedMessage("There is none person in the list with this uuid", 4);

        $permission = Permission::query()
            ->where('user_uuid', $user_uuid)
            ->where('place_id', $place_id);

        if ($permission->value('status') == null)
            return $this->responseDeniedMessage("Permission is not available", 5);

        $is_permitted = $permission->value('status');

        if ($is_permitted != null) {
            if ($is_permitted == true) {
                $valid_user = User::query()->where('id', $user_id);

                /*  $query_uuid = $this->addToQueryHistory(0, $valid_user->value('uuid')
                      , $request->user()->uuid, $permission->value('place_id'));*/

                $query_uuid = $this->addToQueryHistory(status: 0, user_uuid: $valid_user->value('uuid')
                    , controller_uuid: $request->user()->uuid, place_uuid: $place_id);

                return response()->json(["message" => "", "status" => 0, "query_token" => $query_uuid]);

                /* return response()->json(["message" => "", "status" => 0,
                     "first_name" => $valid_user->value('first_name'),
                     "id_number" => $valid_user->value('id_number'),
                     "last_name" => $valid_user->value('last_name'),
                     "user_photo_path" => $valid_user->value('user_photo_path'),
                     "role" => $valid_user->value('role')]);*/
            } else {
                $this->addToQueryHistory(3, $user_uuid, $request->user()->uuid, $permission->value('place_id'));
                return $this->responseDeniedMessage("Permission is not available", 5);
            }
        } else {
            $this->addToQueryHistory(3, $user_uuid, $request->user()->uuid, $permission->value('place_id'));
            return $this->responseDeniedMessage("Your permission is denied", 6);
        }

    }

    private function responseDeniedMessage($message, $status): JsonResponse
    {
        if ($message == null || $status == null)
            response()->json(["message" => "Unknown error is occurred", "status" => 300]);

        return response()->json(["message" => $message, "status" => $status]);
    }

    private function addToQueryHistory($status, $user_uuid, $controller_uuid, $place_uuid): string
    {
        $query_uuid = Uuid::uuid();
        $valid_time = 30;
        BarcodeQuery::query()->create([
            'status' => $status,
            'user_uuid' => $user_uuid,
            'valid_time' => $valid_time,
            'expire_time' => time() + $valid_time,
            'uuid' => $query_uuid,
            'controller_uuid' => $controller_uuid,
            'place_uuid' => $place_uuid
        ]);
        return $query_uuid;
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

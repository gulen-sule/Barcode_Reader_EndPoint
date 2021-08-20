<?php

namespace App\Http\Controllers;

use App\Models\BarcodeToken;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function response;

class UserController extends Controller
{

    public function sendBarcodeToken(Request $request): JsonResponse
    {
        $exp = 30;
        $barcode_token = Str::random(30);
        $user_id = $request->user()->id;
        BarcodeToken::query()->where('user_id', $user_id)->
        update(['barcode_token' => $barcode_token, 'expire_time' => \time() + $exp]);

        return response()->json(['token' => $barcode_token, 'time' => $exp]);
    }


}

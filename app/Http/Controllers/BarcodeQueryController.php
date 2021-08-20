<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BarcodeQuery;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\ArrayList;
header('Access-Control-Allow-Origin: *');

class BarcodeQueryController extends Controller
{
    function getPlacesQueries($place_id): JsonResponse
    {
        $queries = $this->getQueries($place_id);
        return response()->json($queries);
    }

    function getAdminPlaceQueries(Request $request): JsonResponse
    {
        header("Access-Control-Allow-Origin: *");

        if ($request->user() == null)
            return \response()->json(["status" => 401]);
        if ($request->user()->role != 3)
            return \response()->json(["status" => 400]);

        $user_uuid = $request->user()->uuid;
        if ($user_uuid == null)
            return \response()->json(["status" => 401]);
        $place_uuid = Admin::query()->where('user_uuid', $user_uuid)->value('place_uuid');

        if ($place_uuid == null)
            return \response()->json(["status" => 400]);

        $queries=$this->getQueries($place_uuid);
        return response()->json($queries);
    }

    function getQueries($place_uuid): \ArrayIterator
    {
        $output = (new ArrayList())->getIterator();
        $queries = BarcodeQuery::query()->where('place_uuid', $place_uuid)->get('*');

        foreach ($queries as $q) {
            $user = \App\Models\User::query()->
            where('uuid', $q->value('user_uuid'))->value('first_name');
            //\dump($query->value('controller_uuid'));
            $controller = \App\Models\User::query()->
            where('uuid', $q->value('controller_uuid'))->value('first_name');

            $place = Place::query()->where('uuid', $q->value('place_uuid'));
            $q = ['uuid' => $q->value('uuid'), 'status' => $q->value('status')
                , "controller" => $controller, 'user' => $user, 'place' => $place->
                value('name'), 'place_address' => $place->value('address')];
            $output->append($q);
        }
        return $output;

    }
}

<?php

namespace App\Http\Controllers\Wheel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use WheelConfigLogic;

class WheelConfigController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
    public function index(Request $request): AnonymousResourceCollection {
        return WheelConfigLogic::index($request);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param $ids
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function update($ids, Request $request): JsonResponse {
		return WheelConfigLogic::singleRecordUpdate($ids, $request);
    }

}

<?php

namespace App\Http\Controllers\Wheel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Controller;
use WheelPrizeLogic;

class WheelPrizeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection {
		return WheelPrizeLogic::index($request);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request): JsonResponse {
		return WheelPrizeLogic::store($request);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param $id
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function update($id, Request $request): JsonResponse {
		return WheelPrizeLogic::singleRecordUpdate($id, $request);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $ids
	 * @return JsonResponse
	 * @throws \Exception
	 */
	public function destroy($ids): JsonResponse {
		if (is_array($ids)) {
			return WheelPrizeLogic::multipleRecordDestroy($ids);
		}
		return WheelPrizeLogic::singleRecordDestroy($ids);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function destroys(Request $request): JsonResponse {
		return WheelPrizeLogic::multipleRecordDestroy($request->all());
	}
}

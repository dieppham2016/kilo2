<?php

namespace App\Http\Controllers\Wheel;

use App\Http\Controllers\Controller;
use App\Models\WheelPattern;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use WheelPatternLogic;

class WheelPatternController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection {
		return WheelPatternLogic::index($request);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function store(Request $request): JsonResponse {
		return WheelPatternLogic::store($request);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param $id
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function update($id, Request $request): JsonResponse {
		return WheelPatternLogic::singleRecordUpdate($id, $request);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $ids
	 * @return JsonResponse
	 */
	public function destroy($ids): JsonResponse {
		return WheelPatternLogic::singleRecordDestroy($ids);
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function destroys(Request $request): JsonResponse {
		return WheelPatternLogic::multipleRecordDestroy($request->all());
	}
}

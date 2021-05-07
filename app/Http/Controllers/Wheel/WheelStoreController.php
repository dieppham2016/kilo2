<?php

namespace App\Http\Controllers\Wheel;

use App\Models\WheelStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use WheelStoreLogic;

class WheelStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection {
        return WheelStoreLogic::index($request);
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param $id
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function update($id, Request $request): JsonResponse {
        return WheelStoreLogic::singleRecordUpdate($id, $request);
    }
}

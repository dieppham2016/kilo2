<?php

namespace App\Http\Controllers\Wheel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use WheelChangeLogLogic;

class WheelChangeLogController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
    public function index(Request $request): AnonymousResourceCollection {
        return WheelChangeLogLogic::index($request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response {
        //
    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param $ids
	 * @param Request $request
	 * @return Response
	 */
    public function update($ids,Request $request): Response {
        //
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param $ids
	 * @return Response
	 */
    public function destroy($ids): Response {
        //
    }
}

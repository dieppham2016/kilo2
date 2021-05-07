<?php

namespace App\Http\Controllers;

use App\Http\Resources\WheelPatternResource;
use App\Http\Resources\WheelPrizeCollectionResource;
use App\Models\WheelPrizePattern;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class WheelPrizePatternController extends Controller {
	const ITEM_PER_PAGE = 10;

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return AnonymousResourceCollection
	 */
	public function index(Request $request): AnonymousResourceCollection {
		$searchParams = $request->all();
		$limit = Arr::get($searchParams, 'limit');

		$query = WheelPrizePattern::query();
		$query->with('prizes');
		if ($limit) {
			$patterns = $query->paginate($limit);
			return WheelPatternResource::collection($patterns);
		} else {
			$patterns = $query->get();
			return WheelPatternResource::collection($patterns)->additional(['meta' => ['total' => count($patterns)]]);
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return Response
	 */
	public function store(Request $request) {
		$pattern = new WheelPrizePattern();
		$pattern->pattern_name = $request->pattern_name;
		$pattern->pattern_alias = $request->pattern_alias;
		$pattern->max_prize = $request->max_prize;
		$pattern->prize_location = $request->prize_location;
		$pattern->bonus_min = $request->bonus_min;
		$pattern->bonus_max = $request->bonus_max;
		$pattern->bonus_fixed = $request->bonus_fixed;
		$pattern->bonus_current = $request->bonus_current;
		$pattern->bonus_enable = $request->bonus_enable;
		$pattern->bonus_increament = $request->bonus_increament;
		$pattern->save();

//		$pattern->prizes()->sync($request->prize_location, FALSE);

		return response()->json(['status' => 200, 'data' => ['message' => WheelPrizePattern::message($pattern)]]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\WheelPrizePattern $wheelPrizeCollection
	 * @return Response
	 */
	public function show(WheelPrizePattern $wheelPrizeCollection) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\WheelPrizePattern $wheelPrizeCollection
	 * @return Response
	 */
	public function edit(WheelPrizePattern $wheelPrizeCollection) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \App\Models\WheelPrizePattern $wheelPrizeCollection
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update($id, Request $request) {
		$pattern = WheelPrizePattern::find($id);
		if ($pattern) {
//			$pattern->update(
//				[
//					'pattern_name' => $request->pattern_name,
//					'pattern_alias' => $request->pattern_alias,
//					'max_prize' => $request->max_prize,
//					'prize_location' => $request->prize_location,
//					'bonus_min' => $request->bonus_min,
//					'bonus_max' => $request->bonus_max,
//					'bonus_fixed' => $request->bonus_fixed,
//					'bonus_current' => $request->bonus_current,
//					'bonus_enable' => $request->bonus_enable,
//					'bonus_increament' => $request->bonus_increament,
//				]
//			);
			$pa = $request->all();
			$idPrizes = [];
			foreach ($pa['prize_location'] as $key => $value) {
				array_push($idPrizes, $value);
			}
			$pattern->update($pa);
			$pattern->prizes()->detach();
			$pattern->prizes()->sync($idPrizes, FALSE);
			return response()->json(['status' => 200, 'data' => ['message' => WheelPrizePattern::message($pattern)]]);
		}
		return \response()->json(['status' => 500, 'data' => ['message' => 'objectNotExist']]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\WheelPrizePattern $wheelPrizeCollection
	 * @return Response
	 */
	public function destroy(WheelPrizePattern $wheelPrizeCollection) {
		//
	}
}

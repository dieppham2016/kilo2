<?php


namespace App\Nkid\ModelLogic;

class JsonResponse extends \Illuminate\Http\JsonResponse {

	public static function message($status, $message): \Illuminate\Http\JsonResponse {
		return \response()->json(['status' => $status, 'data' => ['message' => $message]]);
	}

	public static function collection($status, $collection) {
		return $collection->additional(
			[
				'meta' => ['total' => count($collection->resource)],
				'status' => $status,
			]
		);
	}

	public static function data($status, $data): \Illuminate\Http\JsonResponse
    {
	    return response()->json(['data' => $data, 'status' => $status]);
    }

	public static function paginate($status, $collection) {
		return $collection->additional(['status' => $status]);
	}

}

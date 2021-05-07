<?php

namespace App\Http\Controllers;

use App\Events\BillPushed;
use App\Models\Bill;
use App\Http\Resources\BillResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class BillController extends Controller
{
    const ITEM_PER_PAGE = 10;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return array|AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $limit = Arr::get($searchParams, 'limit');

        $query = Bill::query();
        $query = Bill::whereBetweenDate($query, Arr::get($searchParams, 'date'));
        $query = Bill::whereStatus($query, Arr::get($searchParams, 'status'));
        if ($limit) {
            $bill = $query->paginate($limit);
            return BillResource::collection($bill);
        }
        else {
            $bill = $query->get();
            return BillResource::collection($bill)->additional(['meta' => ['total' => count($bill)]]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $bill = new Bill();
        $bill->bill_no = $request->bill_no;
        $bill->status_id = $request->status_id;
        $bill->save();
        event(new BillPushed($request->get('status'), $bill));
        return response()->json(['status' => 200]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $bill = Bill::findOrFail($id);
        return \response()->json(['data' => $bill]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function update($id, Request $request)
    {
        $bill = Bill::findOrFail($id);
        $bill->status_id = $request->status_id;
        $bill->save();
	    event(new BillPushed($request->get('status'), $bill));
        return response()->json(['status' => 200]);
    }

    public function updates(Request $request)
    {
        $ids_to_update = array_map(function ($item) {
            return $item['id'];
        }, $request->all());
        $item_update = array_map(function ($item) {
            return [
              'status_id' => $item['status'],
            ];
        }, $request->all());
        Bill::whereIn('id', $ids_to_update)->update($item_update[0]);
        return \response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();
        return response()->json(['data' => $bill->bill_no]);
    }

    public function destroys(Request $request)
    {
        if (is_array($request->all())) {
            $ids_to_delete = array_map(function ($item) {
                return $item['id'];
            }, $request->all());
            Bill::whereIn('id', $ids_to_delete)->delete();
            return response()->json(['data' => true]);
        }
    }
}

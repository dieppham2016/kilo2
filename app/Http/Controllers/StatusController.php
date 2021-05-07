<?php

namespace App\Http\Controllers;

use App\Http\Resources\BillStatusResource;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class StatusController extends Controller
{
    const ITEM_PER_PAGE = 10;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $query = Status::query();
        $limit = Arr::get($search, 'limit', static::ITEM_PER_PAGE);
        return BillStatusResource::collection($query->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
        $status = new Status();
        $status->name = $request->name;
        $status->description = $request->description;
        $status->display_level = $request->display_level;
        $status->save();
        return response()->json(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $status = Status::findOrFail($id);
        return response()->json(['status' => 200, 'data' => $status]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Status $status
     * @return void
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
        $status = Status::findOrFail($id);
        $status->update($request->all());
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();
        return response()->json(['status' => 'success']);
    }
}

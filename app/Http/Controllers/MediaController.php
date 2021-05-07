<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $param = $request->all();
        $query = Media::query();
        $limit = Arr::get($param, 'limit');
        if (Arr::exists($param, 'type')) {
            $query->where('type', Arr::get($param, 'type'));
        }
        return MediaResource::collection($query->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        return \response()->json(['status' => 201, 'data' => $request->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Media $gallery
     * @return Response
     */
    public function show(Media $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Media $gallery
     * @return Response
     */
    public function edit(Media $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Media $gallery
     * @return Response
     */
    public function update(Request $request, Media $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $gallery
     * @return Response
     */
    public function destroy(Media $gallery)
    {
        //
    }
}

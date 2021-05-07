<?php

namespace App\Http\Controllers;

use App\Http\Resources\QueueContentResource;
use App\Http\Resources\QueueResource;
use App\Models\QueueContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class QueueContentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index()
    {
        $queueContent = QueueContent::get()->first();
        $collections = [];
        if ($queueContent) {
            $media = [];
            $media_collections = $queueContent->collection()->get();
            foreach ($media_collections as $collection) {
                $collection_id = $collection->id;
                $collection_original_name = $collection->original_name;
                $collection_display_name = $collection->display_name;
                $media = $collection->getMedia($collection_original_name);
                $media_item = [];
                foreach ($media as $item) {
                    $media_item[] = [
                        'id' => $item->id,
                        'url' => $item->getFullUrl(),
                        'name' => $item->name,
                    ];
                }
                $collections[] = [
                    'id' => $collection_id,
                    'original_name' => $collection_original_name,
                    'display_name' => $collection_display_name,
                    'item' => $media_item,
                ];
            }
            $queueContent['collections'] = $collections;
            return \response()->json(['data' => $queueContent, 'status' => 200]);
        } else {
            return \response()->json(['status' => 204]);
        }

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
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        dump($request->all());
//        $queue = QueueContent::findOrCreate();
    }

    /**
     * Display the specified resource.
     *
     * @param QueueContent $queueContent
     * @return Response
     */
    public function show(QueueContent $queueContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param QueueContent $queueContent
     * @return Response
     */
    public function edit(QueueContent $queueContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function update($id, Request $request)
    {
        $queue = QueueContent::get()->first();
        if ($queue == null) {
            $queue = QueueContent::create([
                'header_vi' => $request->header_vi,
                'header_en' => $request->header_en,
                'mode_active' => $request->mode_active
            ]);
            $queue->collection()->sync($request->collections);
        } else {
            $queue->update([
                'header_vi' => $request->header_vi,
                'header_en' => $request->header_en,
                'mode_active' => $request->mode_active
            ]);
            $queue->collection()->sync($request->collections);
        }

        return \response()->json(['status' => 200]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param QueueContent $queueContent
     * @return Response
     */
    public function destroy(QueueContent $queueContent)
    {
        //
    }
}

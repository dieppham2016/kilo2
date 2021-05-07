<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileMediaResource;
use App\Models\MediaCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MediaCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $option = $request->all();
        $option_type = Arr::get($option, 'type');
        $query = MediaCollection::query();
        if (isset($option_type)) {
            $query = $query->where('type', $option_type);
        }
        $media = $query->get();
        $collection = [];
        foreach ($media as $item) {
            $mediaItem = $item->getMedia($item->original_name);
            $files = [];
            foreach ($mediaItem as $image) {
                $files[] = new FileMediaResource($image);
            }
            $collection[] = [
                'id' => $item->id,
                'count' => $mediaItem->count(),
                'original_name' => $item->original_name,
                'display_name' => $item->display_name,
                'files' => $files,
            ];

        }
        return \response()->json(['data' => $collection]);
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
     * @return void
     */
    public function store(Request $request)
    {
        $param = $request->all();
        $name = Arr::get($param, 'collection_name');
        $expected_file_type = Arr::get($param, 'expected_file');
        if ($request->hasFile('file')) {
            $file_type = preg_split('/\//', $request->file('file')->getClientMimeType())[0];
            if ($file_type == $expected_file_type) {
                $media = MediaCollection::where('display_name', $name)->firstOr(function () use ($request, $name, $file_type) {
                    $media = new MediaCollection();
                    $media->original_name = $name;
                    $media->display_name = $name;
                    $media->type = $file_type;
                    $media->save();

                    $media->addMediaFromRequest('file')->toMediaCollection($name);

                });
                if (isset($media)) {
                    $media->addMediaFromRequest('file')->toMediaCollection($media->original_name);
                }
            }

        }
//        dump($request->all());
//        return response()->json(['data' => $request->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param MediaCollection $mediaCollection
     * @return Response
     */
    public function show(MediaCollection $mediaCollection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MediaCollection $mediaCollection
     * @return Response
     */
    public function edit(MediaCollection $mediaCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse|void
     */
    public function update($id, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'display_name' => 'unique:media_collections,display_name'
        ]);
        if ($validate->fails()) {
            return \response()->json(['status' => 204, 'message' => $request->display_name . ' đã tồn tại.']);
        } else {
            $media = MediaCollection::findOrFail($id);
            $media->display_name = $request->display_name;
            $media->save();
            return \response()->json(['status' => 200, 'message' => $request->display_name . ' đã cập nhật.']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $levelDelete = Arr::get($request->all(), 'level');
        if ($levelDelete == 'image') {
            $collectionOriginalName = Arr::get($request->all(), 'original_name');
            $media = MediaCollection::whereOriginalName($collectionOriginalName)->get();
            $mediaItem = $media[0]->getMedia($collectionOriginalName);
            foreach ($mediaItem as $item) {
                if ($id == $item->id){
                    $item->delete();
                    break;
                }
            }
        } else if($levelDelete == 'collection') {
            $media = MediaCollection::findOrFail($id);
            $media->delete();
        }

        return \response()->json(['status' => 200]);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method getFullUrl()
 * @property mixed id
 * @property mixed name
 * @property mixed mime_type
 * @property mixed size
 * @property mixed manipulations
 * @property mixed custom_properties
 * @property mixed order_column
 */
class FileMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $request->all();
        dump($image);
        return [
            'id' => $this->id,
            'url' => $this->getFullUrl(),
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'manipulations' => $this->manipulations,
            'custom_properties' => $this->custom_properties,
            'order_column' => $this->order_column,
        ];
    }
    public static function collection($resource)
    {
        return [
            'id' => $resource->id,
            'url' => $resource->getUrl(),
            'name' => $resource->name,
            'mime_type' => $resource->mime_type,
            'size' => $resource->size,
            'manipulations' => $resource->manipulations,
            'custom_properties' => $resource->custom_properties,
            'order_column' => $resource->order_column,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QueueContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'header_vi' => $this->header_vi,
            'header_en' => $this->header_en,
            'collection_ids' => json_decode($this->collection_ids),
            'mode_active' => $this->mode_active
        ];
    }
}

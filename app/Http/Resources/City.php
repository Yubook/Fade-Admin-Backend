<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function App\Helpers\getUploadImage;

class City extends JsonResource
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
            'id' => $this->id ?? NULL,
            'name' => $this->name ?? "",
            'state_id' => $this->state_id ?? NULL,
            'country_id' => $this->country_id ?? NULL,
            'latitude' => $this->latitude ?? "",
            'longitude' => $this->longitude ?? "",
            'active' => $this->active,
        ];
    }
}

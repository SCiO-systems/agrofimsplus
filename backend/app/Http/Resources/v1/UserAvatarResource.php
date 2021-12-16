<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAvatarResource extends JsonResource
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
            'avatar_url' => empty($this->avatar_url)
                ? null
                : secure_asset("storage/" . $this->avatar_url),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

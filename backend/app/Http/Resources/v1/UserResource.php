<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'role' => $this->role,
            'identity_provider' => $this->identity_provider,
            'avatar_url' => empty($this->avatar_url)
                ? null
                : secure_asset("storage/" . $this->avatar_url),
            'ui_language' => $this->ui_language,
            'ui_language_display_format' => $this->ui_language_display_format,
            'ui_date_display_format' => $this->ui_date_display_format,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

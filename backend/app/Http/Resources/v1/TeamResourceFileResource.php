<?php

namespace App\Http\Resources\v1;

use Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceFileResource extends JsonResource
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
            'path' => $this->path,
            'filename' => $this->filename,
            'pii_check_status' => $this->pii_check_status,
            'pii_terms_accepted_at' => $this->pii_terms_accepted_at,
            'url' => Storage::url($this->path),
        ];
    }
}

<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Storage;

class TeamResourceThumbnailResource extends JsonResource
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
            'url' => Storage::temporaryUrl(
                $this->path,
                now()->addSeconds(env('PRESIGNED_URL_TTL_IN_SECONDS', 86400))
            ),
        ];
    }
}

<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\v1\InviteOwnerResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class InviteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $owner = $this->team->owner;

        return [
            'id' => $this->id,
            'team' => new TeamResource($this->team),
            'inviter' => new InviteOwnerResource($owner),
        ];
    }
}

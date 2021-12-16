<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceWithUsersAndOwner extends JsonResource
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
            'name' => $this->name,
            'owner' => new TeamOwnerResource($this->owner),
            'users' => TeamUserResource::collection($this->users()->get()),
            'description' => $this->description,
        ];
    }
}

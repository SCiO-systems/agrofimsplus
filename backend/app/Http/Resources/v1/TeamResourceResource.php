<?php

namespace App\Http\Resources\v1;

use App\Models\CollectionResource;
use DB;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResourceResource extends JsonResource
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
            'team_id' => $this->team_id,
            'version' => $this->version,
            'external_metadata_record_id' => $this->external_metadata_record_id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'subtype' => $this->subtype,
            'status' => $this->status,
            'comments' => $this->comments,
            'findable_score' => $this->findable_score,
            'accessible_score' => $this->accessible_score,
            'interoperable_score' => $this->interoperable_score,
            'reusable_score' => $this->reusable_score,
            'fair_scoring' => $this->fair_scoring,
            'published_at' => $this->published_at,
            'issued_at' => $this->issued_at,
            'author_id' => $this->author_id,
            'publisher_id' => $this->publisher_id,
            'collections' => TeamResourceCollectionsResource::collection($this->collections()->get()),
            'collections_count' => $this->collections->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

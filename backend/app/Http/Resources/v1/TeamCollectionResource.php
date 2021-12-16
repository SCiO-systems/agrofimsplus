<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamCollectionResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'inherit_information_to_resources' => $this->inherit_information_to_resources,
            'keywords_extracted_from_resources' => $this->keywords_extracted_from_resources,
            'publish_as_catalogue_of_resources' => $this->publish_as_catalogue_of_resources,
            'doi' => $this->doi,
            'publisher' => $this->publisher,
            'embargo_date' => $this->embargo_date,
            'geospatial_coverage_calculated_from_resources' => $this->geospatial_coverage_calculated_from_resources,
            'temporal_coverage_calculated_from_resources' => $this->temporal_coverage_calculated_from_resources,
            'findable_score' => $this->findable_score,
            'accessible_score' => $this->accessible_score,
            'interoperable_score' => $this->interoperable_score,
            'reusable_score' => $this->reusable_score,
            'fair_scoring' => $this->fair_scoring,
            'resources_count' => $this->resources->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

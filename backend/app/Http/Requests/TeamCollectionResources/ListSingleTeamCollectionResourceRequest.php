<?php

namespace App\Http\Requests\TeamCollectionResources;

use App\Models\Resource;
use App\Rules\ResourceStatusValidationRule;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ListSingleTeamCollectionResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization parameters.
        $isLoggedIn = Auth::check();
        $isTeamMember = Auth::user()->teams->contains($this->team->id);
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        $collectionBelongsToTeam = $this->collection->team_id === $this->team->id;
        $resourceBelongsToCollection = $this->collection->resources->contains($this->resource->id);

        return $isLoggedIn
            && ($isTeamMember || $isTeamOwner)
            && $collectionBelongsToTeam
            && $resourceBelongsToCollection;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => ['nullable', new ResourceStatusValidationRule]
        ];
    }
}

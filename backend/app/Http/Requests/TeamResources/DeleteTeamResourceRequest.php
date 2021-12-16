<?php

namespace App\Http\Requests\TeamResources;

use App\Enums\ResourceStatus;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTeamResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization parameters.
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        $isResourceOwner = $this->resource->author_id === Auth::user()->id;
        $resourceBelongsToTeam = $this->resource->team_id === $this->team->id;
        // We also need to check for the resource status but this will be done in the controller
        // in order to return the proper error message there.
        return $resourceBelongsToTeam && ($isResourceOwner || $isTeamOwner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}

<?php

namespace App\Http\Requests\TeamResources;

use App\Enums\ResourceStatus;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeamResourceRequest extends FormRequest
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
        $belongsToAuthoringTeam = Auth::user()->isPartOfAuthoringTeam($this->resource->id);
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        return $isLoggedIn && ($belongsToAuthoringTeam || $isTeamOwner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'metadata_record' => 'nullable',
            'collections' => 'nullable|array',
            'collections.*' => 'numeric',
            'status' => ['nullable', Rule::in(ResourceStatus::getValues())]
        ];
    }
}

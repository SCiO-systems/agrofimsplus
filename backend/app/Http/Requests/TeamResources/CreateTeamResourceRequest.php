<?php

namespace App\Http\Requests\TeamResources;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamResourceRequest extends FormRequest
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
        $isTeamMember = !empty(Auth::user()->sharedTeams()->find($this->team->id));
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        return $isLoggedIn && ($isTeamMember || $isTeamOwner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            // TODO: Add validation rules based on external service.
            'type' => 'required|string',
            'subtype' => 'required|string',
            'authoring_team' => 'array|required',
            'review_team' => 'array|required',
            'collections' => 'nullable|array',
        ];
    }
}

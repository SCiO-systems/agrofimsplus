<?php

namespace App\Http\Requests\TeamResourceComments;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamResourceCommentsRequest extends FormRequest
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
        $belongsToReviewTeam = Auth::user()->isPartOfAuthoringTeam($this->resource->id);
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        return $isLoggedIn && ($belongsToReviewTeam || $isTeamOwner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comments' => 'nullable|string'
        ];
    }
}

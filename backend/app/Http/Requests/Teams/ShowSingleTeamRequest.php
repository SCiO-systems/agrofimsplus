<?php

namespace App\Http\Requests\Teams;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ShowSingleTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
        return [];
    }
}

<?php

namespace App\Http\Requests\UserTeams;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ShowUserOwnedTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Validate that the user can access the user object
        // that they want to see as well as the fact that they are the owner of the team
        // they want to access.
        $isSameUser = Auth::user()->id === $this->user->id;
        $isTeamOwner = $this->team->owner_id === $this->user->id;
        return $isSameUser && $isTeamOwner;
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

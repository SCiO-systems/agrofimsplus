<?php

namespace App\Http\Requests\TeamInvites;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamInviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if the user who sends the invite is a team owner.
        $isTeamOwner = Auth::user()->id === $this->team->owner_id;
        return $isTeamOwner;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'emails.*' => 'required|email'
        ];
    }
}

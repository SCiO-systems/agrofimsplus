<?php

namespace App\Http\Requests\Teams;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $isLoggedIn = Auth::check();
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        return $isLoggedIn && $isTeamOwner;
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

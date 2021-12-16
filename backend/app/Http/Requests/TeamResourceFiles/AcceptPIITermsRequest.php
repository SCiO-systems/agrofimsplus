<?php

namespace App\Http\Requests\TeamResourceFiles;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AcceptPIITermsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $isLoggedIn = Auth::check();
        $belongsToAuthoringTeam = Auth::user()->isPartOfAuthoringTeam($this->resource->id);
        $isTeamOwner = $this->team->owner_id === Auth::user()->id;
        $isFileOwner = $this->file->user_id === Auth::user()->id;
        return $isLoggedIn && $isFileOwner && ($belongsToAuthoringTeam || $isTeamOwner);
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

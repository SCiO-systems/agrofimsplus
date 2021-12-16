<?php

namespace App\Http\Requests\UserInvites;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserInviteAcceptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if the user is same and the invite belongs to the user.
        $inviteBelongsToUser = Auth::user()->id === $this->invite->user_id;
        $isSameUser = Auth::user()->id == $this->user->id;
        return $isSameUser && $inviteBelongsToUser;
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

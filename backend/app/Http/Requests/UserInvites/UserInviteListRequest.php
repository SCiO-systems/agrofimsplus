<?php

namespace App\Http\Requests\UserInvites;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserInviteListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check if the user is same.
        return Auth::user()->id == $this->user->id;
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

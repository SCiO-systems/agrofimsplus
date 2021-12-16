<?php

namespace App\Http\Requests\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Validate that the user can access the user object
        // that they want to update (eg. themselves).
        return Auth::user()->id === $this->user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            // 'password' => 'nullable|string',
            // 'profile_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // 2MB max file size.
            // TODO: Search in table for the available UI languages.
            'ui_language' => 'nullable|string',
            // TODO: Search in table for the language display format.
            'ui_language_display_format' => 'nullable|string',
            // TODO: Search in table for UI date display format.
            'ui_date_display_format' => 'nullable|string',
        ];
    }
}

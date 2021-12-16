<?php

namespace App\Http\Requests\Doi;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class DoiCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'doi' => 'string|required',
            'title' => 'string|required'
        ];
    }
}

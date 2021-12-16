<?php

namespace App\Http\Requests\SCiO\Mimetypes;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class GetMimetypeRequest extends FormRequest
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
            'filename' => 'string|required',
            'type' => 'string|required|in:dataset'
        ];
    }
}

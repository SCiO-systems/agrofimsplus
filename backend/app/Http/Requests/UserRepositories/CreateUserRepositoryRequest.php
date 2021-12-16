<?php

namespace App\Http\Requests\UserRepositories;

use App\Enums\RepositoryType;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRepositoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Validate that the user can access the user object
        // that they want to see (eg. themselves).
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
            'name' => 'string|required',
            'type' => Rule::in(RepositoryType::getValues()),
            'client_secret' => 'string|required',
            'api_endpoint' => 'url|required',
        ];
    }
}

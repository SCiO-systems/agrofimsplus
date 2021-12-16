<?php

namespace App\Rules;

use App\Enums\ResourceStatus;
use Illuminate\Contracts\Validation\Rule;

class ResourceStatusValidationRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, ResourceStatus::getValues()) || empty($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be one of the following: ' . implode(
            ', ',
            ResourceStatus::getValues()
        );
    }
}

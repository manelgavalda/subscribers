<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ActiveDomain implements Rule
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
        $domain = substr($value, strpos($value, '@') + 1);

        return checkdnsrr($domain, "A");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email domain must be active.';
    }
}

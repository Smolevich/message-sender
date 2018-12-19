<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UserMessageType implements Rule
{
    const AVAILABLE_TYPE_MESSAGE = [
        'telegram',
        'whatsapp',
        'viber',
    ];

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!in_array($value, self::AVAILABLE_TYPE_MESSAGE)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid type of messenger. Available values: '. implode(',', self::AVAILABLE_TYPE_MESSAGE);
    }
}

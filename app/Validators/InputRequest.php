<?php

namespace App\Validators;

use App\Enum\Constants;
use App\Rules\UserMessageType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InputRequest
{
    const AVAILABLE_TYPE_MESSAGE = [
        'telegram',
        'whatsapp',
        'viber',
    ];
    const TELEGRAM_TYPE_MESSAGE = 'telegram';
    const VIBER_TYPE_MESSAGE = 'viber';
    const WHATSAPP_TYPE_MESSAGE = 'whatsapp';

    protected $config;

    public function __construct()
    {
        $this->config = $config = [
            'text' => ['required','string', 'max:240'],
            'users' => ['required', 'array'],
            'users.*.user_id' => ['required', 'string'],
            'users.*.type' => [
                'required',
                'string',
                new UserMessageType()
            ],
            'users.*.timeout' => [
                'required',
                'integer',
                'min:1',
                'max:300'
            ]
        ];
    }

    protected $errors = [];

    /**
     * Validate input data
     *
     * @param array $inputData
     * @return boolean
     */
    public function validate(array $inputData): bool
    {
        $validator = Validator::make($inputData, $this->config);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->toArray();

            return false;
        }

        return true;
    }

    /**
     * Return errors if validation has errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}

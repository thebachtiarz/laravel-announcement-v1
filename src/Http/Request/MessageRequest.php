<?php

namespace TheBachtiarz\Announcement\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use TheBachtiarz\Announcement\Interfaces\ValidatorInterface;

class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return ValidatorInterface::ANNOUNCEMENT_MESSAGE_RULES;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return ValidatorInterface::ANNOUNCEMENT_MESSAGE_MESSAGES;
    }
}

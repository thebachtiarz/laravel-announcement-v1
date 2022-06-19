<?php

namespace TheBachtiarz\Announcement\Interfaces;

use TheBachtiarz\Toolkit\Helper\Interfaces\Validator\GlobalValidatorInterface;

interface ValidatorInterface
{
    // ? Rules

    public const ANNOUNCEMENT_CODE_RULES = [
        'code' => ["required", "alpha_num"]
    ];

    public const ANNOUNCEMENT_MESSAGE_RULES = [
        'message' => ["required", "string", GlobalValidatorInterface::RULES_REGEX_SENTENCES]
    ];

    // ? Messages

    public const ANNOUNCEMENT_CODE_MESSAGES = [
        'code.*' => 'Announcement code invalid'
    ];

    public const ANNOUNCEMENT_MESSAGE_MESSAGES = [
        'message.*' => 'Announcement message invalid'
    ];
}

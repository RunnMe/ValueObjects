<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\EmailSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\EmailValidator;

/**
 * Email string value class
 *
 * Class EmailValue
 * @package Runn\ValueObjects\Values
 */
class EmailValue
    extends StringValue
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new EmailValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new EmailSanitizer();
    }

}

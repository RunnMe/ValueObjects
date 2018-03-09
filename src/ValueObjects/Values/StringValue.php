<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\StringSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\StringValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple string value class
 *
 * Class StringValue
 * @package Runn\ValueObjects\Values
 */
class StringValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new StringValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new StringSanitizer();
    }

}

<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\StringSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\StringValidator;

/**
 * Simple string value class
 *
 * Class StringValue
 * @package Runn\ValueObjects
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
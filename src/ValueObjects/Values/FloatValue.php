<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\FloatSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\FloatValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple float number value class
 *
 * Class FloatValue
 * @package Runn\ValueObjects\Values
 */
class FloatValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new FloatValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new FloatSanitizer();
    }

}

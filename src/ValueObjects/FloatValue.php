<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\FloatSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\FloatValidator;

/**
 * Class FloatValue
 * @package Runn\ValueObjects
 */
class FloatValue
    extends SimpleValueObject
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
<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\BooleanSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\BooleanValidator;

/**
 * Class BooleanValue
 * @package Runn\ValueObjects
 */
class BooleanValue
    extends SimpleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new BooleanValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new BooleanSanitizer();
    }

}
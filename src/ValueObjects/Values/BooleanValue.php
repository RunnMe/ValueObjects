<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\BooleanSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\BooleanValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple boolean value class
 *
 * Class BooleanValue
 * @package Runn\ValueObjects\Values
 */
class BooleanValue
    extends SingleValueObject
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

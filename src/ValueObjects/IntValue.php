<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\IntSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\IntValidator;

/**
 * Simple integer number  value class
 *
 * Class IntValue
 * @package Runn\ValueObjects
 */
class IntValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new IntValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new IntSanitizer();
    }

}
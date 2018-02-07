<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\UuidSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\UuidValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple UUID value class
 *
 * Class UuidValue
 * @package Runn\ValueObjects\Values
 */
class UuidValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new UuidValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new UuidSanitizer();
    }

}

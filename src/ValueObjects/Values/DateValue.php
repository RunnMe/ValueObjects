<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\DateSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\DateValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple date value class
 *
 * Class DateValue
 * @package Runn\ValueObjects\Values
 */
class DateValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new DateValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new DateSanitizer();
    }

    /**
     * JsonSerializable implementation
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue()->format('Y-m-d');
    }

}

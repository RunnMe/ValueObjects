<?php

namespace Runn\ValueObjects\Values;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\DateTimeSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\DateTimeValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple date and time value class
 *
 * Class DateTimeValue
 * @package Runn\ValueObjects\Values
 */
class DateTimeValue
    extends SingleValueObject
{

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new DateTimeValidator();
    }

    /**
     * @return \Runn\Sanitization\Sanitizer
     */
    protected function getDefaultSanitizer(): Sanitizer
    {
        return new DateTimeSanitizer();
    }

    /**
     * JsonSerializable implementation
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue()->format('c');
    }

}

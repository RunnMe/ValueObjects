<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\DateTimeSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\DateTimeValidator;

/**
 * Simple date and time value class
 *
 * Class DateTimeValue
 * @package Runn\ValueObjects
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
     * @return string
     */
    public function getValue()
    {
        return parent::getValue()->format('c');
    }

}
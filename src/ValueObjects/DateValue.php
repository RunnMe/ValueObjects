<?php

namespace Runn\ValueObjects;

use Runn\Sanitization\Sanitizer;
use Runn\Sanitization\Sanitizers\DateSanitizer;
use Runn\Validation\Validator;
use Runn\Validation\Validators\DateValidator;

/**
 * Simple date value class
 *
 * Class DateValue
 * @package Runn\ValueObjects
 */
class DateValue
    extends SimpleValueObject
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
     * @return string
     */
    public function getValue()
    {
        return parent::getValue()->format('Y-m-d');
    }

}
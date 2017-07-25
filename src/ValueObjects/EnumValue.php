<?php

namespace Runn\ValueObjects;

use Runn\Validation\Validator;
use Runn\Validation\Validators\EnumValidator;

/**
 * Simple enumerated value class
 *
 * Class EnumValue
 * @package Runn\ValueObjects
 */
abstract class EnumValue
    extends SingleValueObject
{

    const VALUES = [];

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new EnumValidator(static::VALUES);
    }

}
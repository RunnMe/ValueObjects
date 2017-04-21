<?php

namespace Runn\ValueObjects;

use Runn\Validation\Validator;
use Runn\Validation\Validators\EnumValidator;

/**
 * Class EnumValue
 * @package Runn\ValueObjects
 */
abstract class EnumValue
    extends SimpleValueObject
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
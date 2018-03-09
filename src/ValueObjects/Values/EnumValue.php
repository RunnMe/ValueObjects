<?php

namespace Runn\ValueObjects\Values;

use Runn\Validation\Validator;
use Runn\Validation\Validators\EnumValidator;
use Runn\ValueObjects\SingleValueObject;

/**
 * Simple enumerated value class
 *
 * Class EnumValue
 * @package Runn\ValueObjects\Values
 */
abstract class EnumValue
    extends SingleValueObject
{

    // @7.1
    /*public */const VALUES = [];

    /**
     * @return \Runn\Validation\Validator
     */
    protected function getDefaultValidator(): Validator
    {
        return new EnumValidator(static::VALUES);
    }

}

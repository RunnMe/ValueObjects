<?php

namespace Runn\ValueObjects\Errors;

use Runn\Core\Exceptions;

/**
 * Class ComplexValueObjectErrors
 * @package Runn\ValueObjects\Errors
 */
class ComplexValueObjectErrors
    extends Exceptions
{

    public static function getType()
    {
        return ComplexValueObjectError::class;
    }

}
<?php

namespace Runn\ValueObjects\Errors;

use Runn\Core\Exceptions;

/**
 * Complex value object errors collection
 *
 * Class ComplexValueObjectErrors
 * @package Runn\ValueObjects\Errors
 */
class ComplexValueObjectErrors extends Exceptions
{
    /**
     * @return string
     */
    public static function getType(): string
    {
        return ComplexValueObjectErrorInterface::class;
    }
}

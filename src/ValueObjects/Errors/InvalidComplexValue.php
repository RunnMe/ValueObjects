<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;

/**
 * Invalid complex value error
 *
 * Class InvalidComplexValue
 * @package Runn\ValueObjects\Errors
 */
class InvalidComplexValue
    extends Exception
    implements ComplexValueObjectErrorInterface
{
}

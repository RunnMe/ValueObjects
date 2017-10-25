<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Class InvalidComplexValue
 * @package Runn\ValueObjects\Errors
 *
 * Invalid complex value error
 */
class InvalidComplexValue
    extends Exception
    implements ComplexValueObjectError
{}
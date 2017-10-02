<?php

namespace Runn\ValueObjects\Errors;

/**
 * Interface ComplexValueObjectError
 * @package Runn\ValueObjects\Errors
 */
interface ComplexValueObjectError
    extends \Throwable
{

    /**
     * @return string
     */
    public function getField(): string;

}
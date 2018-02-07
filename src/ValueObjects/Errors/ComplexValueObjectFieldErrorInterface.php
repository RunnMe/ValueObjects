<?php

namespace Runn\ValueObjects\Errors;

/**
 * Common interface for complex value field errors
 *
 * Interface ComplexValueObjectFieldErrorInterface
 * @package Runn\ValueObjects\Errors
 */
interface ComplexValueObjectFieldErrorInterface
    extends ComplexValueObjectErrorInterface
{

    /**
     * @return string
     */
    public function getField(): string;

}

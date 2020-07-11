<?php

namespace Runn\ValueObjects\Errors;

/**
 * Common interface for complex value field errors
 *
 * Interface ComplexValueObjectFieldErrorInterface
 * @package Runn\ValueObjects\Errors
 */
interface ComplexValueObjectFieldErrorInterface extends ComplexValueObjectErrorInterface
{

    /**
     * @param string $key
     * @return static
     */
    public function setField(string $key);

    /**
     * @return string
     */
    public function getField(): string;

}

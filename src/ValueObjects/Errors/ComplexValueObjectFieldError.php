<?php

namespace Runn\ValueObjects\Errors;

/**
 * Interface ComplexValueObjectFieldError
 * @package Runn\ValueObjects\Errors
 */
interface ComplexValueObjectFieldError
    extends ComplexValueObjectError
{

    /**
     * @return string
     */
    public function getField(): string;

}
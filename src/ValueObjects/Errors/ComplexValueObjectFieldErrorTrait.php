<?php

namespace Runn\ValueObjects\Errors;

/**
 * Common interface for complex value field errors default implementation
 *
 * trait ComplexValueObjectFieldErrorTrait
 * @package Runn\ValueObjects\Errors
 */
trait ComplexValueObjectFieldErrorTrait
{

    protected/*@7.4 ?string */ $field = null;

    /**
     * @param string $key
     * @return static
     */
    public function setField(string $key)
    {
        $this->field = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getField(): ?string
    {
        return $this->field;
    }

}

<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Invalid complex value object field's key error
 *
 * Class InvalidFieldKey
 * @package Runn\ValueObjects\Errors
 */
class InvalidFieldKey extends Exception implements ComplexValueObjectFieldErrorInterface
{

    protected $field;

    /**
     * InvalidFieldValue constructor.
     * @param string $field
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @internal param int $value
     */
    public function __construct(string $field, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->field = $field;
        parent::__construct($message, $code, $previous);
    }

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
    public function getField(): string
    {
        return $this->field;
    }

}

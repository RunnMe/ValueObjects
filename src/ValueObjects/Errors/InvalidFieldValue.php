<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Invalid complex value object field's value error
 *
 * Class InvalidFieldValue
 * @package Runn\ValueObjects\Errors
 */
class InvalidFieldValue extends Exception implements ComplexValueObjectFieldErrorInterface
{

    use ComplexValueObjectFieldErrorTrait;

    protected $value;

    /**
     * InvalidFieldValue constructor.
     * @param string $field
     * @param int $value
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $field, $value, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->setField($field);
        $this->value = $value;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

}

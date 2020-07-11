<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;

/**
 * Empty complex value object field's class name error
 * Class EmptyFieldClass
 * @package Runn\ValueObjects\Errors
 *
 */
class EmptyFieldClass extends Exception implements ComplexValueObjectFieldErrorInterface
{

    use ComplexValueObjectFieldErrorTrait;

    /**
     * EmptyFieldClass constructor.
     *
     * @param string $field
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $field, $message = "", $code = 0, \Throwable $previous = null)
    {
        $this->setField($field);
        parent::__construct($message, $code, $previous);
    }

}

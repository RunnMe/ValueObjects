<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Invalid complex value object field's class error
 *
 * Class EmptyFieldClass
 * @package Runn\ValueObjects\Errors
 *
 */
class InvalidFieldClass extends Exception implements ComplexValueObjectFieldErrorInterface
{

    use ComplexValueObjectFieldErrorTrait;

    protected/*@7.4 string*/ $class;

    /**
     * EmptyFieldClass constructor.
     * @param string $field
     * @param string $class
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @internal param int $value
     */
    public function __construct(string $field, string $class, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->setField($field);
        $this->class = $class;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}

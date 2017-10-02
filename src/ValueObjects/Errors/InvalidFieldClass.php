<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Class EmptyFieldClass
 * @package Runn\ValueObjects\Errors
 *
 * Invalid field class error
 */
class InvalidFieldClass
    extends Exception
    implements ComplexValueObjectError
{

    protected $field;
    protected $class;

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
        $this->field = $field;
        $this->class = $class;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}
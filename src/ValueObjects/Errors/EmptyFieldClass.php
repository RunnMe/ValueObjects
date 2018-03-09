<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;

/**
 * Empty complex value object field's class name error
 * Class EmptyFieldClass
 * @package Runn\ValueObjects\Errors
 *
 */
class EmptyFieldClass
    extends Exception
    implements ComplexValueObjectFieldErrorInterface
{

    protected $field;

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
        $this->field = $field;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

}

<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Class EmptyFieldClass
 * @package Runn\ValueObjects\Errors
 *
 * Field class is empty error
 */
class EmptyFieldClass
    extends Exception
    implements ComplexValueObjectError
{

    protected $field;

    /**
     * EmptyFieldClass constructor.
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
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

}
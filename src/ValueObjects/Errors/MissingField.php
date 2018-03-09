<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Missing field error
 *
 * Class MissingField
 * @package Runn\ValueObjects\Errors
 */
class MissingField
    extends Exception
    implements ComplexValueObjectFieldErrorInterface
{

    protected $field;

    /**
     * MissingField constructor.
     * @param string $field
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
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

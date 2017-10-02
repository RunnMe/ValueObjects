<?php

namespace Runn\ValueObjects\Errors;

use Runn\ValueObjects\Exception;
use Throwable;

/**
 * Class InvalidFieldValue
 * @package Runn\ValueObjects\Errors
 *
 * Class holds info about one field in complex value object error
 */
class InvalidFieldValue
    extends Exception
{

    protected $field;
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
        $this->field = $field;
        $this->value = $value;
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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

}
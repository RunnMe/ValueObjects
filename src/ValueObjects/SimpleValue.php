<?php

namespace Runn\ValueObjects;

/**
 * Abstract simple value-object class
 *
 * Class SimpleValue
 * @package Runn\ValueObjects
 */
abstract class SimpleValue
    implements \JsonSerializable
{

    protected $value;

    /**
     * @return static
     */
    public static function new($value = null)
    {
        return new static($value);
    }

    /**
     * SimpleValue constructor.
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * @param mixed $value
     * @return $this
     */
    protected function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return @(string)$this->getValue();
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }

}
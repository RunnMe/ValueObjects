<?php

namespace Runn\ValueObjects;

/**
 * ValueObjectInterface implementation
 *
 * Trait ValueObjectTrait
 * @package Runn\ValueObjects
 *
 * @implements \Runn\ValueObjects\ValueObjectInterface
 */
trait ValueObjectTrait
{

    protected $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $value
     * @return bool
     */
    public function isSame(ValueObjectInterface $value): bool
    {
        return (get_class($value) === get_class($this)) && ($value->getValue() === $this->getValue());
    }

}
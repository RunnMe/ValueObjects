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
     * "Static constructor"
     * @param mixed $value
     * @return self
     */
    public static function new($value = null)
    {
        return new static($value);
    }

    /**
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
     * @param \Runn\ValueObjects\ValueObjectInterface $value
     * @return bool
     */
    public function isSame(ValueObjectInterface $value): bool
    {
        return (get_class($value) === get_class($this)) && ($value->getValue() === $this->getValue());
    }

}
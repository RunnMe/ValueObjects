<?php

namespace Runn\ValueObjects;

/**
 * ValueObjectInterface default implementation
 *
 * Trait ValueObjectTrait
 * @package Runn\ValueObjects
 *
 * @implements \Runn\ValueObjects\ValueObjectInterface
 */
trait ValueObjectTrait
    //implements ValueObjectInterface
{

    protected $__value;

    /**
     * @return array
     */
    protected function notgetters(): array
    {
        return ['schema', 'value', 'field', 'fieldsList', 'object'];
    }

    /**
     * @return array
     */
    protected function notsetters(): array
    {
        return ['schema', 'value', 'field', 'fieldsList'];
    }

    /**
     * "Static constructor"
     *
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
        $this->__value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->__value;
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
    public function __toString(): string
    {
        return $this->getValue() ?? '';
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
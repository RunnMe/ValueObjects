<?php

namespace Runn\ValueObjects;

use Runn\Core\TypedCollection;

/**
 * Collection of value objects
 *
 * Class ValueObjectsCollection
 * @package Runn\ValueObjects
 */
abstract class ValueObjectsCollection
    extends TypedCollection
    implements ValueObjectInterface
{

    public static function getType()
    {
        return ValueObjectInterface::class;
    }

    /**
     * Does value need cast to this (or another) class?
     * @param mixed $value
     * @return bool
     */
    protected function needCasting($key, $value): bool
    {
        if (is_null($value) || is_scalar($value)) {
            return true;
        }
        return parent::needCasting($key, $value);
    }

    public function innerCast($key, $value)
    {
        $class = static::getType();
        return new $class($value);
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $ret = [];
        foreach ($this as $key => $el) {
            $ret[$key] = null !== $el ? $el->getValue() : null;
        }
        return $ret;
    }

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $value
     * @return bool
     */
    public function isEqual(ValueObjectInterface $value): bool
    {
        return (get_class($value) === get_class($this)) && ($value->getValue() === $this->getValue());
    }

}
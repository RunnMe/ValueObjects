<?php

namespace Runn\ValueObjects;

use Runn\Core\TypedCollection;

/**
 * Typed collection of value objects
 *
 * Class ValueObjectsCollection
 * @package Runn\ValueObjects
 */
abstract class ValueObjectsCollection
    extends TypedCollection
    implements ValueObjectInterface
{

    use ValueObjectTrait;

    /**
     * @param iterable|null $data
     *
     */
    public function __construct($data = null)
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public static function getType()
    {
        return ValueObjectInterface::class;
    }

    /**
     * @return array
     */
    protected function notgetters(): array
    {
        return ['type', 'value'];
    }

    /**
     * @return array
     */
    protected function notsetters(): array
    {
        return ['value'];
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

}

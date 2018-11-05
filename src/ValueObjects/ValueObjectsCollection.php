<?php

namespace Runn\ValueObjects;

use Runn\Core\TypedCollection;

/**
 * Typed collection of value objects
 *
 * Class ValueObjectsCollection
 * @package Runn\ValueObjects
 */
abstract class ValueObjectsCollection extends TypedCollection implements ValueObjectInterface
{

    use ValueObjectTrait;

    /**
     * @param iterable|null $data
     */
    public function __construct(/*iterable*/ $data = null)
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return ValueObjectInterface::class;
    }

    /**
     * @return array
     */
    protected function notGetters(): array
    {
        return ['type', 'value'];
    }

    /**
     * @return array
     */
    protected function notSetters(): array
    {
        return ['value'];
    }

    /**
     * Does value need cast to this (or another) class?
     * @param mixed $key
     * @param mixed $value
     * @return bool
     */
    protected function needCasting($key, $value): bool
    {
        if (null === $value || is_scalar($value)) {
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
    public function getValue(): array
    {
        $ret = [];
        foreach ($this as $key => $el) {
            /** @var ComplexValueObject $el */
            $ret[$key] = null !== $el ? $el->getValue() : null;
        }
        return $ret;
    }
}

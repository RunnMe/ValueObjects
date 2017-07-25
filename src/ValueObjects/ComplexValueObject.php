<?php

namespace Runn\ValueObjects;

use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetInterface;
use Runn\Core\StdGetSetTrait;

/**
 * Complex value object consists of one or more columns with values
 *
 * Class ComplexValueObject
 * @package Runn\ValueObjects
 */
abstract class ComplexValueObject
    implements ObjectAsArrayInterface, StdGetSetInterface, ValueObjectInterface
{

    use StdGetSetTrait {
        innerGet as protected traitInnerGet;
    }

    /**
     * @var array
     */
    protected static $schema = [];

    /**
     * @return array
     */
    public static function getSchema()
    {
        return static::$schema;
    }

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
     * Std constructor.
     * @param null $value
     * @throws Exception
     * @internal param iterable|null $data
     */
    public function __construct(/*iterable */$data = null)
    {
        if (null !== $data) {
            $this->fromArray($data);
        }

        foreach (static::getSchema() as $key => $schema) {
            if (!isset($this->$key)) {
                if (array_key_exists('default', $schema)) {
                    $default = $schema['default'];
                    if (null !== $default && $this->needCasting($key, $default)) {
                        $default = $this->innerCast($key, $default);
                    }
                    $this->innerSet($key, $default);
                } else {
                    throw new Exception('Missing complex value object member "' . $key . '"');
                }
            }
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function needCasting($key, $value): bool
    {
        if (null === $value) {
            if (isset(static::getSchema()[$key])) {
                $schema = static::getSchema()[$key];
                if (array_key_exists('default', $schema) && null === $schema['default']) {
                    return false;
                }
            }
        }
        if ($value instanceof ValueObjectInterface) {
            return false;
        }
        return true;
    }

    protected function innerCast($key, $value)
    {
        if (!array_key_exists($key, static::getSchema())) {
            throw new Exception('Invalid complex value object member "' . $key . '"');
        }

        if (empty(static::getSchema()[$key]['class'])) {
            throw new Exception('Empty complex value object member "' . $key . '" class');
        }

        $class = static::getSchema()[$key]['class'];

        if (!is_subclass_of($class, ValueObjectInterface::class)) {
            throw new Exception('Invalid complex value object member "' . $key . '" class');
        }

        return new $class($value);
    }

    protected function innerGet($key)
    {
        if (in_array($key, ['value'], true)) {
            return $this->__data[$key] ?? null;
        } else {
            return $this->traitInnerGet($key);
        }
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
     * @deprecated use trait!
     * @return mixed
     */
    public function __invoke()
    {
        return $this->getValue();
    }

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return bool
     */
    public function isSame(ValueObjectInterface $object): bool
    {
        return (get_class($object) === get_class($this)) && ($object->getValue() === $this->getValue());
    }

    /**
     * Is used to avoid null values serialization
     * @return array|ø
     */
    public function jsonSerialize()
    {
        return array_filter($this->getValue());
    }

}
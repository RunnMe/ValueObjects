<?php

namespace Runn\ValueObjects;

use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetInterface;
use Runn\Core\StdGetSetTrait;

/**
 * Complex value object consists of one or more fields with values
 *
 * Class ComplexValueObject
 * @package Runn\ValueObjects
 *
 */
abstract class ComplexValueObject
    implements ValueObjectInterface, ObjectAsArrayInterface, StdGetSetInterface
{

    use ValueObjectTrait, StdGetSetTrait
    {
        ValueObjectTrait::notgetters insteadof StdGetSetTrait;
        ValueObjectTrait::notsetters insteadof StdGetSetTrait;
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

    protected function setValue(/*iterable */$data = null)
    {
        $schema = static::getSchema();
        if (empty($data)) {
            $data = [];
        }

        foreach ($data as $key => $val) {
            if (!array_key_exists($key, $schema)) {
                throw new Exception('Invalid complex value object field key: "' . $key . '"');
            }
            if ($this->needCasting($key, $val)) {
                $val = $this->innerCast($key, $val);
            }
            $this->innerSet($key, $val);
        }

        foreach ($schema as $key => $field) {
            if (!isset($this->$key)) {
                if (array_key_exists('default', $field)) {
                    $default = $field['default'];
                    if (null !== $default && $this->needCasting($key, $default)) {
                        $default = $this->innerCast($key, $default);
                    }
                    $this->innerSet($key, $default);
                } else {
                    throw new Exception('Missing complex value object field "' . $key . '"');
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
            throw new Exception('Invalid complex value object field key: "' . $key . '"');
        }

        if (empty(static::getSchema()[$key]['class'])) {
            throw new Exception('Empty complex value object field "' . $key . '" class');
        }

        $class = static::getSchema()[$key]['class'];

        if (!is_subclass_of($class, ValueObjectInterface::class)) {
            throw new Exception('Invalid complex value object field "' . $key . '" class');
        }

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
     * JsonSerializable implementation
     * Is used to avoid null values serialization
     * @return array|null
     */
    public function jsonSerialize()
    {
        return array_filter($this->getValue());
    }

}
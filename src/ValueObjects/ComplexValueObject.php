<?php

namespace Runn\ValueObjects;

use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetInterface;
use Runn\Core\StdGetSetTrait;

/**
 * Complex value object consists of one or more fields with values
 * Immutable
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
        StdGetSetTrait::innerSet as trait_innerSet;
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
     * @var bool
     */
    protected $constructed = false;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
        $this->constructed = true;
    }

    /**
     * @param iterable|null $data
     * @throws \Runn\ValueObjects\Exception
     *
     * @7.1
     */
    protected function setValue(/*iterable */$data = null)
    {
        if (empty($data)) {
            $data = [];
        }
        $schema = static::getSchema();

        foreach ($data as $key => $val) {
            if (!array_key_exists($key, $schema)) {
                throw new Exception('Invalid complex value object field key: "' . $key . '"');
            }
            $this->setField($key, $val);
        }

        foreach ($schema as $key => $field) {
            if (!isset($this->$key)) {
                if (!array_key_exists('default', $field)) {
                    throw new Exception('Missing complex value object field "' . $key . '"');
                }
                $this->setField($key, $field['default']);
            }
        }
    }

    protected function setField($field, $value)
    {
        if ($this->constructed) {
            throw new Exception('Can not set field "' . $field . '" value because of value object is constructed');
        }
        if ($this->needCasting($field, $value)) {
            $value = $this->innerCast($field, $value);
        }
        $this->trait_innerSet($field, $value);
    }

    protected function innerSet($key, $val)
    {
        $this->setField($key, $val);
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
<?php

namespace Runn\ValueObjects;

use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\StdGetSetInterface;
use Runn\Core\StdGetSetTrait;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\EmptyFieldClass;
use Runn\ValueObjects\Errors\InvalidComplexValue;
use Runn\ValueObjects\Errors\InvalidField;
use Runn\ValueObjects\Errors\InvalidFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Errors\MissingField;

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
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     *
     * @7.1
     */
    protected function setValue(iterable $data = null)
    {
        if (empty($data)) {
            $data = [];
        }
        $schema = static::getSchema();

        $errors = new ComplexValueObjectErrors;

        foreach ($data as $key => $val) {
            try {
                $this->$key = $val;
            // @7.1
            } catch (InvalidField | EmptyFieldClass | InvalidFieldClass $exception) {
                $errors->add($exception);
            } catch (\Throwable $exception) {
                $errors->add(
                    new InvalidFieldValue($key, $val, 'Invalid complex value object field "' . $key . '" value', 0, $exception)
                );
            }
        }

        foreach ($schema as $key => $field) {
            if (!isset($this->$key)) {
                if (!array_key_exists('default', $field)) {
                    $errors[] = new MissingField($key, 'Missing complex value object field "' . $key . '"');
                    continue;
                }
                $this->$key = $field['default'];
            }
        }

        if (!$errors->empty()) {
            throw $errors;
        }

        try {

            $res = $this->validate();

            if (false === $res) {
                $errors[] = new InvalidComplexValue('Invalid complex value');
            } elseif ($res instanceof \Generator) {
                $exceptionType = ComplexValueObjectErrors::getType();
                foreach ($res as $error) {
                    if ($error instanceof $exceptionType) {
                        $errors->add($error);
                    }
                }
            }

        } catch (\Throwable $e) {
            $errors->add($e);
            throw $errors;
        }

        if (!$errors->empty()) {
            throw $errors;
        }

    }

    protected function innerSet($key, $val)
    {
        $this->setField($key, $val);
    }

    protected function setField($field, $value)
    {
        if (!array_key_exists($field, static::getSchema())) {
            throw new InvalidField($field,'Invalid complex value object field key: "' . $field . '"');
        }

        if ($this->constructed) {
            throw new Exception('Can not set field "' . $field . '" value because of value object is constructed');
        }

        if ($this->needCasting($field, $value)) {
            $value = $this->innerCast($field, $value);
        }

        $this->trait_innerSet($field, $value);
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
        if (empty(static::getSchema()[$key]['class'])) {
            throw new EmptyFieldClass($key, 'Empty complex value object field "' . $key . '" class');
        }

        $class = static::getSchema()[$key]['class'];

        if (!is_subclass_of($class, ValueObjectInterface::class)) {
            throw new InvalidFieldClass($key, $class, 'Invalid complex value object field "' . $key . '" class');
        }

        return new $class($value);
    }

    /**
     * @return bool|\Generator
     * @throws \Throwable|\Runn\Core\Exceptions
     */
    protected function validate()
    {
        return true;
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
        $ret = [];
        foreach ($this as $key => $val) {
            if (null !== $val) {
                if ($val instanceof \JsonSerializable) {
                    $ret[$key] = $val->jsonSerialize();
                } else {
                    $ret[$key] = $val;
                }
            }
        }
        return $ret;
    }

}
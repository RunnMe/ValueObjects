<?php

namespace Runn\ValueObjects;

use Runn\Core\ObjectAsArrayInterface;

/**
 * Complex Value Object with primary key consists of one or more fields of this object
 * Default primary key field name is '__id'
 * Mutable except it's primary key fields
 *
 * Class Entity
 * @package Runn\ValueObjects
 */
abstract class Entity
    extends ComplexValueObject
    implements EntityInterface
{

    // @7.1
    /*protected */const PK_FIELDS = ['__id'];

    /**
     * @return array
     */
    public static function getPrimaryKeyFields(): array
    {
        return static::PK_FIELDS;
    }

    /**
     * This method returns "true" if primary key is scalar
     * @return bool
     */
    public static function isPrimaryKeyScalar(): bool
    {
        return 1 === count(static::getPrimaryKeyFields());
    }

    /**
     * This method tells about primary key is already set (all ones fields are not null)
     * @return bool
     */
    public function issetPrimaryKey(): bool
    {
        foreach (static::getPrimaryKeyFields() as $field) {
            if (null !== $this->$field) {
                return true;
            }
        }
        return false;
    }

    /**
     * This method can return either single scalar value or an array consisting of all PK fields' values
     * @return mixed|array
     */
    public function getPrimaryKey()
    {
        $ret = [];
        foreach (static::getPrimaryKeyFields() as $field) {
            $ret[$field] = $this->$field ? $this->$field->getValue() : null;
        }
        if (empty(array_filter($ret))) {
            return null;
        } elseif (1 == count($ret)) {
            return array_shift($ret);
        }
        return $ret;
    }

    /**
     * This method checks if $data can be used as primary key value
     * @param mixed $data
     * @return bool
     */
    public static function conformsToPrimaryKey($data): bool
    {
        if (null === $data) {
            return true;
        }
        $fields = static::getPrimaryKeyFields();
        if (1 === count($fields)) {
            if (is_scalar($data)) {
                return true;
            }
            return 1 === count($data) && (isset($data[$fields[0]]) || isset($data->{$fields[0]}));
        }
        if (is_array($data) || ($data instanceof ObjectAsArrayInterface)) {
            if (is_array($data)) {
                $keys = array_keys($data);
            } else {
                $keys = $data->keys();
            }
            return empty(array_diff($keys, $fields)) && empty(array_diff($fields, $keys));
        }
        return false;
    }

    /**
     * @return array
     */
    public static function getFieldsListWoPk()
    {
        return array_values(array_diff(static::getFieldsList(), static::getPrimaryKeyFields()));
    }

    /**
     * All fields except primary key are required!
     * @return array
     */
    protected static function getRequiredFieldsList()
    {
        return static::getFieldsListWoPk();
    }

    protected function setField($field, $value)
    {
        if ($this->constructed) {
            if ($this->issetPrimaryKey() && in_array($field, static::getPrimaryKeyFields())) {
                throw new Exception('Can not set field "' . $field . '" value because of it is part of primary key which is already set');
            }
        }
        if ($this->needCasting($field, $value)) {
            $value = $this->innerCast($field, $value);
        }
        $this->trait_innerSet($field, $value);
    }

    /**
     * @param \Runn\ValueObjects\EntityInterface $object
     * @return bool
     */
    public function isEqual(EntityInterface $object): bool
    {
        return (get_class($object) === get_class($this)) && ($object->getPrimaryKey() == $this->getPrimaryKey() && (null !== $object->getPrimaryKey()));
    }

}
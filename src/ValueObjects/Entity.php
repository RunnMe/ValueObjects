<?php

namespace Runn\ValueObjects;

/**
 * Complex Value Object with primary key consists of one or more fields of this object
 * Default primary key field name is '__id'
 *
 * Class Entity
 * @package Runn\ValueObjects
 */
abstract class Entity
    extends ComplexValueObject
    implements EntityInterface
{

    const PK_FIELDS = ['__id'];

    /**
     * @return array
     */
    public static function getPrimaryKeyFields(): array
    {
        return static::PK_FIELDS;
    }

    /**
     * @return mixed|array
     */
    public function getPrimaryKey()
    {
        $ret = [];
        foreach (static::getPrimaryKeyFields() as $field) {
            $ret[$field] = $this->$field->getValue();
        }
        if (empty($ret)) {
            return null;
        } elseif (1 == count($ret)) {
            return array_shift($ret);
        }
        return $ret;
    }

    protected function setField($field, $value)
    {
        if ($this->constructed) {
            if (in_array($field, static::getPrimaryKeyFields())) {
                throw new Exception('Can not set field "' . $field . '" value because of it is part of primary key');
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
        return (get_class($object) === get_class($this)) && ($object->getPrimaryKey() == $this->getPrimaryKey());
    }

}
<?php

namespace Runn\ValueObjects;

/**
 * Complex Value Object with primary key consists of one or more columns of this object
 * Default primary key column name id '__id'
 *
 * Class Entity
 * @package Runn\ValueObjects
 */
abstract class Entity
    extends ComplexValueObject
    implements ValueObjectCastingInterface
{

    const PK_COLUMNS = ['__id'];

    /**
     * @return array
     */
    public static function getPrimaryKeyColumns()
    {
        return static::PK_COLUMNS;
    }

    /**
     * @return array
     */
    public function getPrimaryKey()
    {
        $ret = [];
        foreach (static::getPrimaryKeyColumns() as $column) {
            $ret[$column] = $this->$column->getValue();
        }
        return $ret;
    }

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return bool
     */
    public function isSame(ValueObjectInterface $object): bool
    {
        return (get_class($object) === get_class($this)) && ($object->getPrimaryKey() == $this->getPrimaryKey());
    }

    public static function getValueObjectClass()
    {
        return ComplexValueObject::class;
    }

    /**
     * @param string|null $class
     * @return \Runn\ValueObjects\ValueObjectInterface
     * @throws \Runn\ValueObjects\Exception
     */
    public function toValueObject($class = null): ValueObjectInterface
    {
        if (null === $class) {
            $class = static::getValueObjectClass();
        }
        if (!is_a($class, ComplexValueObject::class, true)) {
            throw new Exception('Invalid complex value object class');
        }

        $data   = $this->getValue();
        $schema = static::getSchema();
        foreach (static::getPrimaryKeyColumns() as $column) {
            unset($data[$column]);
            unset($schema[$column]);
        }

        if ($class === ComplexValueObject::class) {
            $classDef = 'extends \\Runn\\ValueObjects\\ComplexValueObject { protected static $schema = ' . var_export($schema, true) . ';};';
            return eval('return new class(' . var_export($data, true) . ') ' . $classDef);
        } else {
            return new $class($data);
        }
    }

    public static function fromValueObject(ValueObjectInterface $source, array $primaryKey = [])
    {
        return new static($primaryKey + $source->getValue());
    }

}
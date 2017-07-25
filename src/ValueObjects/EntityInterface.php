<?php

namespace Runn\ValueObjects;

/**
 * Interface for Entities
 *
 * Interface EntityInterface
 * @package Runn\ValueObjects
 *
 * @codeCoverageIgnore
 */
interface EntityInterface
    extends ValueObjectInterface
{

    /**
     * This method always returns an array
     * @return array
     */
    public static function getPrimaryKeyFields(): array;

    /**
     * This method can return either single scalar value or an array consisting of all PK fields' values
     * @return mixed
     */
    public function getPrimaryKey();

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return bool
     */
    public function isSame(ValueObjectInterface $object): bool;

    /**
     * @param \Runn\ValueObjects\EntityInterface $object
     * @return bool
     */
    public function isEqual(EntityInterface $object): bool;

}
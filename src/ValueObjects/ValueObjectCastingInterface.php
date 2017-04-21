<?php

namespace Runn\ValueObjects;

/**
 * Interface is designed for object that can casts to ValueObject and be casted from ValueObject
 *
 * Interface ValueObjectCastingInterface
 * @package Runn\ValueObjects
 *
 * @codeCoverageIgnore
 */
interface ValueObjectCastingInterface
{

    /**
     * @return \Runn\ValueObjects\ValueObjectInterface
     */
    public function toValueObject(): ValueObjectInterface;

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $source
     * @return mixed
     */
    public static function fromValueObject(ValueObjectInterface $source);

}
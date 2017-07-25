<?php

namespace Runn\ValueObjects;

/**
 * Common interface for Simple and Complex Value Objects
 *
 * Interface ValueObjectInterface
 * @package Runn\ValueObjects
 *
 * @codeCoverageIgnore
 */
interface ValueObjectInterface
{

    /**
     * ValueObjectInterface constructor.
     * @param mixed $value
     */
    public function __construct($value);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return bool
     */
    public function isSame(ValueObjectInterface $object): bool;

}
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
     * @return mixed
     */
    public function getValue();

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $value
     * @return bool
     */
    public function isEqual(self $value): bool;

}
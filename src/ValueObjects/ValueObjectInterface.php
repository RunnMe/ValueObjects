<?php

namespace Runn\ValueObjects;

/**
 * Common interface for Single and Complex Value Objects
 *
 * Interface ValueObjectInterface
 * @package Runn\ValueObjects
 *
 * @codeCoverageIgnore
 */
interface ValueObjectInterface
{

    /**
     * "Static constructor"
     *
     * @param mixed $value
     * @return self
     */
    public static function new($value = null);

    /**
     * @param mixed $value
     */
    public function __construct($value = null);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function __invoke();

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return bool
     */
    public function isSame(ValueObjectInterface $object): bool;

}

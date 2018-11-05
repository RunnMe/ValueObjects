<?php

namespace Runn\ValueObjects;

/**
 * Interface ValueObjectAwareInterface
 * @package Runn\ValueObjects
 *
 * @codeCoverageIgnore
 */
interface ValueObjectAwareInterface
{
    /**
     * @param \Runn\ValueObjects\ValueObjectInterface $object
     * @return $this
     */
    public function setValueObject(ValueObjectInterface $object): self;

    /**
     * @return \Runn\ValueObjects\ValueObjectInterface
     */
    public function getValueObject(): ValueObjectInterface;
}

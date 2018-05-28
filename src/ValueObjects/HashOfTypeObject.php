<?php

namespace Runn\ValueObjects;

use Runn\ValueObjects\Errors\InvalidComplexValue;

abstract class HashOfTypeObject extends ComplexValueObject
{
    /**
     * Returns type of value object field
     * @return string
     */
    public static function getFieldType(): string
    {
        return '';
    }

    /**
     * HashOfTypeObject constructor.
     * @param array|null $value
     * @throws InvalidComplexValue
     */
    public function __construct($value = null)
    {
        if (\is_array($value)) {
            $definitionString = ['class' => self::getFieldType()];
            foreach (array_keys($value) as $key) {
                self::$schema[$key] = $definitionString;
            }

            parent::__construct($value);
        }
        throw new InvalidComplexValue();
    }
}

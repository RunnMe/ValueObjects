<?php

namespace Runn\ValueObjects\Values;

use Runn\ValueObjects\HashOfTypeObject;

class HashOfStringValues extends HashOfTypeObject
{
    public static function getFieldType(): string
    {
        return StringValue::class;
    }
}

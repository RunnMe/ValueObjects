<?php

namespace Runn\tests\ValueObjects\Values\UuidValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\EmptyValue;
use Runn\Validation\Exceptions\InvalidUuid;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\Values\UuidValue;

class UuidValueTest extends TestCase
{

    public function testEmpty()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new UuidValue();
    }

    public function testInvalidString()
    {
        $this->expectException(InvalidUuid::class);
        $valueObject = new UuidValue(42);
    }

    public function testInvalidUuid()
    {
        $this->expectException(InvalidUuid::class);
        $valueObject = new UuidValue('foo');
    }

    public function testConstruct()
    {
        $valueObject = new UuidValue('e3b9876f-86e4-4895-8648-1b6ee8091786');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(UuidValue::class, $valueObject);

        $this->assertIsString($valueObject->getValue());
        $this->assertSame('{E3B9876F-86E4-4895-8648-1B6EE8091786}', $valueObject->getValue());
    }
}

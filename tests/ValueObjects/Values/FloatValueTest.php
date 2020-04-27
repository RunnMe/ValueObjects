<?php

namespace Runn\tests\ValueObjects\Values\FloatValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\InvalidFloat;
use Runn\ValueObjects\Values\FloatValue;

class FloatValueTest extends TestCase
{

    public function testNull()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new FloatValue(42);

        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(42.0, $valueObject->getValue());

        $valueObject = new FloatValue(3.14159);

        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(3.14159, $valueObject->getValue());

        $valueObject = new FloatValue(1.2e34);

        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(1.2e34, $valueObject->getValue());
    }

    public function testBooleanFalse()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue(false);
    }

    public function testBooleanTrue()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue(true);
    }

    public function testInt()
    {
        $valueObject = new FloatValue(0);
        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(0.0, $valueObject->getValue());

        $valueObject = new FloatValue(42);
        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(42.0, $valueObject->getValue());
    }

    public function testEmptyString()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue('');
    }

    public function testString()
    {
        $valueObject = new FloatValue('3.14159');
        $this->assertIsFloat($valueObject->getValue());
        $this->assertSame(3.14159, $valueObject->getValue());
    }

    public function testArray()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue([1, 2, 3]);
    }

    public function testObject()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue(new class {});
    }

    public function testResource()
    {
        $this->expectException(InvalidFloat::class);
        $valueObject = new FloatValue(fopen('php://input', 'r'));
    }

}

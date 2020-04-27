<?php

namespace Runn\tests\ValueObjects\Values\IntValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\InvalidInt;
use Runn\ValueObjects\Values\IntValue;

class IntValueTest extends TestCase
{

    public function testNull()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new IntValue(42);

        $this->assertIsInt($valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    public function testBooleanFalse()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(false);
    }

    public function testBooleanTrue()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(true);
    }

    public function testInt()
    {
        $valueObject = new IntValue(0);
        $this->assertIsInt($valueObject->getValue());
        $this->assertSame(0, $valueObject->getValue());

        $valueObject = new IntValue(42);
        $this->assertIsInt($valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    public function testEmptyString()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue('');
    }

    public function testString()
    {
        $valueObject = new IntValue('42');
        $this->assertIsInt($valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    public function testFloat1()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(1.23);
    }

    public function testFloat2()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(1.2e34);
    }

    public function testArray()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue([1, 2, 3]);
    }

    public function testObject()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(new class {});
    }

    public function testResource()
    {
        $this->expectException(InvalidInt::class);
        $valueObject = new IntValue(fopen('php://input', 'r'));
    }

}

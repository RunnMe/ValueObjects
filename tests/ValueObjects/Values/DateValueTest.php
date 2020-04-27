<?php

namespace Runn\tests\ValueObjects\Values\DateValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\EmptyValue;
use Runn\Validation\Exceptions\InvalidDate;
use Runn\ValueObjects\Values\DateValue;

class DateValueTest extends TestCase
{

    public function testNull()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new DateValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertEquals((new \DateTime('2010-01-01')), $valueObject->getValue());
    }

    public function testBooleanFalse()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue(false);
    }

    public function testBooleanTrue()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue(true);
    }

    public function testInt()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue(42);
    }

    public function testEmptyString()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new DateValue('');
    }

    public function testInvalidString()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue('2010-XX-YY');
    }

    public function testArray()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue([1, 2, 3]);
    }

    public function testInvalidObject()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue(new class {});
    }

    public function testResource()
    {
        $this->expectException(InvalidDate::class);
        $valueObject = new DateValue(fopen('php://input', 'r'));
    }

    public function testJson()
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertSame('"2010-01-01"', json_encode($valueObject));
    }

}

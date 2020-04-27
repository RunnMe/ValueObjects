<?php

namespace Runn\tests\ValueObjects\Values\DateTimeValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\EmptyValue;
use Runn\Validation\Exceptions\InvalidDateTime;
use Runn\ValueObjects\Values\DateTimeValue;

class DateTimeValueTest extends TestCase
{

    public function testNull()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new DateTimeValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new DateTimeValue('2010-01-01');
        $this->assertEquals((new \DateTime('2010-01-01')), $valueObject->getValue());

        $valueObject = new DateTimeValue('2010-01-01 12:34:56');
        $this->assertEquals((new \DateTime('2010-01-01 12:34:56')), $valueObject->getValue());
    }

    public function testBooleanFalse()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue(false);
    }

    public function testBooleanTrue()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue(true);
    }

    public function testInt()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue(42);
    }

    public function testEmptyString()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new DateTimeValue('');
    }

    public function testInvalidString()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue('2010-XX-YY');
    }

    public function testArray()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue([1, 2, 3]);
    }

    public function testInvalidObject()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue(new class {});
    }

    public function testResource()
    {
        $this->expectException(InvalidDateTime::class);
        $valueObject = new DateTimeValue(fopen('php://input', 'r'));
    }

    public function testJson()
    {
        $valueObject = new DateTimeValue('2010-01-01');
        $this->assertSame('"2010-01-01T00:00:00' . date('P') . '"', json_encode($valueObject));

        $valueObject = new DateTimeValue('2010-01-01 12:34:56');
        $this->assertSame('"2010-01-01T12:34:56' . date('P') . '"', json_encode($valueObject));
    }

}

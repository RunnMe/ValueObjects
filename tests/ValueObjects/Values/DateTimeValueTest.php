<?php

namespace Runn\tests\ValueObjects\Values\DateTimeValue;

use Runn\ValueObjects\Values\DateTimeValue;

class DateTimeValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testNull()
    {
        $valueObject = new DateTimeValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new DateTimeValue('2010-01-01');
        $this->assertEquals((new \DateTime('2010-01-01')), $valueObject->getValue());

        $valueObject = new DateTimeValue('2010-01-01 12:34:56');
        $this->assertEquals((new \DateTime('2010-01-01 12:34:56')), $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testBooleanFalse()
    {
        $valueObject = new DateTimeValue(false);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testBooleanTrue()
    {
        $valueObject = new DateTimeValue(true);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testInt()
    {
        $valueObject = new DateTimeValue(42);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testEmptyString()
    {
        $valueObject = new DateTimeValue('');
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testInvalidString()
    {
        $valueObject = new DateTimeValue('2010-XX-YY');
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testArray()
    {
        $valueObject = new DateTimeValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testInvalidObject()
    {
        $valueObject = new DateTimeValue(new class {});
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDateTime
     */
    public function testResource()
    {
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
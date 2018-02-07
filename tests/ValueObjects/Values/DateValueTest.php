<?php

namespace Runn\tests\ValueObjects\Values\DateValue;

use Runn\ValueObjects\Values\DateValue;

class DateValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testNull()
    {
        $valueObject = new DateValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertEquals((new \DateTime('2010-01-01')), $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testBooleanFalse()
    {
        $valueObject = new DateValue(false);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testBooleanTrue()
    {
        $valueObject = new DateValue(true);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testInt()
    {
        $valueObject = new DateValue(42);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testEmptyString()
    {
        $valueObject = new DateValue('');
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testInvalidString()
    {
        $valueObject = new DateValue('2010-XX-YY');
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testArray()
    {
        $valueObject = new DateValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testInvalidObject()
    {
        $valueObject = new DateValue(new class {});
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidDate
     */
    public function testResource()
    {
        $valueObject = new DateValue(fopen('php://input', 'r'));
    }

    public function testJson()
    {
        $valueObject = new DateValue('2010-01-01');
        $this->assertSame('"2010-01-01"', json_encode($valueObject));
    }

}
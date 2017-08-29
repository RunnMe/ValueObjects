<?php

namespace Runn\tests\ValueObjects\Values\FloatValue;

use Runn\ValueObjects\Values\FloatValue;

class FloatValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testNull()
    {
        $valueObject = new FloatValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new FloatValue(42);

        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(42.0, $valueObject->getValue());

        $valueObject = new FloatValue(3.14159);

        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(3.14159, $valueObject->getValue());

        $valueObject = new FloatValue(1.2e34);

        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(1.2e34, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testBooleanFalse()
    {
        $valueObject = new FloatValue(false);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testBooleanTrue()
    {
        $valueObject = new FloatValue(true);
    }

    public function testInt()
    {
        $valueObject = new FloatValue(0);
        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(0.0, $valueObject->getValue());

        $valueObject = new FloatValue(42);
        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(42.0, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testEmptyString()
    {
        $valueObject = new FloatValue('');
    }

    public function testString()
    {
        $valueObject = new FloatValue('3.14159');
        $this->assertInternalType('float', $valueObject->getValue());
        $this->assertSame(3.14159, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testArray()
    {
        $valueObject = new FloatValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testObject()
    {
        $valueObject = new FloatValue(new class {});
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidFloat
     */
    public function testResource()
    {
        $valueObject = new FloatValue(fopen('php://input', 'r'));
    }

}
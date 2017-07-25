<?php

namespace Runn\tests\ValueObjects\Values\StringValue;

use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\SingleValueObject;

class StringValueTest extends \PHPUnit_Framework_TestCase
{

    public function testNull()
    {
        $valueObject = new StringValue(null);

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValue::class, $valueObject);

        $this->assertSame('', $valueObject->getValue());
        $this->assertSame('', $valueObject());
    }

    public function testConstruct()
    {
        $valueObject = new StringValue('foo');

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo', $valueObject->getValue());
    }

    public function testBoolean()
    {
        $valueObject = new StringValue(false);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('', $valueObject->getValue());

        $valueObject = new StringValue(true);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('1', $valueObject->getValue());
    }

    public function testInt()
    {
        $valueObject = new StringValue(0);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('0', $valueObject->getValue());

        $valueObject = new StringValue(42);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('42', $valueObject->getValue());
    }

    public function testFloat()
    {
        $valueObject = new StringValue(1.23);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('1.23', $valueObject->getValue());

        $valueObject = new StringValue(1.2e34);
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('1.2E+34', $valueObject->getValue());
    }

    public function testEmptyString()
    {
        $valueObject = new StringValue('');
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('', $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidString
     */
    public function testArray()
    {
        $valueObject = new StringValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidString
     */
    public function testInvalidObject()
    {
        $valueObject = new StringValue(new class {});
    }

    public function testValidObject()
    {
        $valueObject = new StringValue(new class {public function __toString()
        {
            return 'foo';
        }
        });
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo', $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidString
     */
    public function testResource()
    {
        $valueObject = new StringValue(fopen('php://input', 'r'));
    }

}
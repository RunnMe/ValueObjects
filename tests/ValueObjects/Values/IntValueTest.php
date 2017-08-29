<?php

namespace Runn\tests\ValueObjects\Values\IntValue;

use Runn\ValueObjects\Values\IntValue;

class IntValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testNull()
    {
        $valueObject = new IntValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new IntValue(42);

        $this->assertInternalType('integer', $valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testBooleanFalse()
    {
        $valueObject = new IntValue(false);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testBooleanTrue()
    {
        $valueObject = new IntValue(true);
    }

    public function testInt()
    {
        $valueObject = new IntValue(0);
        $this->assertInternalType('integer', $valueObject->getValue());
        $this->assertSame(0, $valueObject->getValue());

        $valueObject = new IntValue(42);
        $this->assertInternalType('integer', $valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testEmptyString()
    {
        $valueObject = new IntValue('');
    }

    public function testString()
    {
        $valueObject = new IntValue('42');
        $this->assertInternalType('integer', $valueObject->getValue());
        $this->assertSame(42, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testFloat1()
    {
        $valueObject = new IntValue(1.23);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testFloat2()
    {
        $valueObject = new IntValue(1.2e34);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testArray()
    {
        $valueObject = new IntValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testObject()
    {
        $valueObject = new IntValue(new class {});
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidInt
     */
    public function testResource()
    {
        $valueObject = new IntValue(fopen('php://input', 'r'));
    }

}
<?php

namespace Runn\tests\ValueObjects\Values\BooleanValue;

use Runn\ValueObjects\Values\BooleanValue;

class BooleanValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidBoolean
     */
    public function testInvalidEmptyArray()
    {
        $valueObject = new BooleanValue([]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidBoolean
     */
    public function testInvalidNotEmptyArray()
    {
        $valueObject = new BooleanValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidBoolean
     */
    public function testInvalidObject()
    {
        $valueObject = new BooleanValue(new class {});
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidBoolean
     */
    public function testInvalidResource()
    {
        $valueObject = new BooleanValue(fopen('php://input', 'r'));
    }

    public function testFalse()
    {
        $valueObject = new BooleanValue(null);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue(false);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('false');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('off');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('no');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('0');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue(0);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());
    }

    public function testTrue()
    {
        $valueObject = new BooleanValue(true);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('true');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('on');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('yes');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('blablabla');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('1');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('42');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('3.14159');
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(1);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(42);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(3.14159);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());
    }

}
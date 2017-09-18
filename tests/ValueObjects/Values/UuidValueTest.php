<?php

namespace Runn\tests\ValueObjects\Values\UuidValue;

use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\Values\UuidValue;

class UuidValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testEmpty()
    {
        $valueObject = new UuidValue();
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidUuid
     */
    public function testInvalidString()
    {
        $valueObject = new UuidValue(42);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidUuid
     */
    public function testInvalidUuid()
    {
        $valueObject = new UuidValue('foo');
    }

    public function testConstruct()
    {
        $valueObject = new UuidValue('e3b9876f-86e4-4895-8648-1b6ee8091786');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(UuidValue::class, $valueObject);

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('{E3B9876F-86E4-4895-8648-1B6EE8091786}', $valueObject->getValue());
    }
}

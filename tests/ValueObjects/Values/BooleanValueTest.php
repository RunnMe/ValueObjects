<?php

namespace Runn\tests\ValueObjects\Values\BooleanValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\InvalidBoolean;
use Runn\ValueObjects\Values\BooleanValue;

class BooleanValueTest extends TestCase
{

    public function testInvalidEmptyArray()
    {
        $this->expectException(InvalidBoolean::class);
        $valueObject = new BooleanValue([]);
    }

    public function testInvalidNotEmptyArray()
    {
        $this->expectException(InvalidBoolean::class);
        $valueObject = new BooleanValue([1, 2, 3]);
    }

    public function testInvalidObject()
    {
        $this->expectException(InvalidBoolean::class);
        $valueObject = new BooleanValue(new class {});
    }

    public function testInvalidResource()
    {
        $this->expectException(InvalidBoolean::class);
        $valueObject = new BooleanValue(fopen('php://input', 'r'));
    }

    public function testFalse()
    {
        $valueObject = new BooleanValue(null);
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue(false);
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('');
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('false');
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('off');
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('no');
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue('0');
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());

        $valueObject = new BooleanValue(0);
        $this->assertIsBool($valueObject->getValue());
        $this->assertFalse($valueObject->getValue());
    }

    public function testTrue()
    {
        $valueObject = new BooleanValue(true);
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('true');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('on');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('yes');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('blablabla');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('1');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('42');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue('3.14159');
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(1);
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(42);
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());

        $valueObject = new BooleanValue(3.14159);
        $this->assertIsBool($valueObject->getValue());
        $this->assertTrue($valueObject->getValue());
    }

}

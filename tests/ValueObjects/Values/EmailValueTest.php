<?php

namespace Runn\tests\ValueObjects\Values\EmailValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\EmptyValue;
use Runn\Validation\Exceptions\InvalidEmail;
use Runn\ValueObjects\Values\EmailValue;
use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\SingleValueObject;

class EmailValueTest extends TestCase
{

    public function testNull()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new EmailValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new EmailValue('foo@bar.baz');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValue::class, $valueObject);
        $this->assertInstanceOf(EmailValue::class, $valueObject);

        $this->assertIsString($valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    /**
     * @todo: fix it, change to InvalidEmail!
     */
    public function testBooleanFalse()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new EmailValue(false);
    }

    public function testBooleanTrue()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue(true);
    }

    public function testInt()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue(42);
    }

    public function testFloat()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue(1.23);
    }

    public function testEmptyString()
    {
        $this->expectException(EmptyValue::class);
        $valueObject = new EmailValue('');
    }

    public function testArray()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue([1, 2, 3]);
    }

    public function testInvalidObject()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue(new class {});
    }

    public function testValidObject()
    {
        $valueObject = new EmailValue(new class {public function __toString()
        {
            return 'foo@bar.baz';
        }
        });
        $this->assertIsString($valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    public function testResource()
    {
        $this->expectException(InvalidEmail::class);
        $valueObject = new EmailValue(fopen('php://input', 'r'));
    }

}

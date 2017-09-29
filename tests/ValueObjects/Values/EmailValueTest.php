<?php

namespace Runn\tests\ValueObjects\Values\EmailValue;

use Runn\Validation\Exceptions\EmptyValue;
use Runn\Validation\Exceptions\InvalidEmail;
use Runn\ValueObjects\Values\EmailValue;
use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\SingleValueObject;

class EmailValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testNull()
    {
        $valueObject = new EmailValue(null);
    }

    public function testConstruct()
    {
        $valueObject = new EmailValue('foo@bar.baz');

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValue::class, $valueObject);
        $this->assertInstanceOf(EmailValue::class, $valueObject);

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testBooleanFalse()
    {
        $valueObject = new EmailValue(false);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testBooleanTrue()
    {
        $valueObject = new EmailValue(true);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testInt()
    {
        $valueObject = new EmailValue(42);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testFloat()
    {
        $valueObject = new EmailValue(1.23);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\EmptyValue
     */
    public function testEmptyString()
    {
        $valueObject = new EmailValue('');
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testArray()
    {
        $valueObject = new EmailValue([1, 2, 3]);
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testInvalidObject()
    {
        $valueObject = new EmailValue(new class {});
    }

    public function testValidObject()
    {
        $valueObject = new EmailValue(new class {public function __toString()
        {
            return 'foo@bar.baz';
        }
        });
        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame('foo@bar.baz', $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\InvalidEmail
     */
    public function testResource()
    {
        $valueObject = new EmailValue(fopen('php://input', 'r'));
    }

}
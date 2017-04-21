<?php

namespace Runn\tests\ValueObjects\SimpleValueObject;

use Runn\Sanitization\Sanitizer;
use Runn\Validation\ValidationError;
use Runn\Validation\Validator;
use Runn\Validation\Validators\PassThruValidator;
use Runn\ValueObjects\IntValue;
use Runn\ValueObjects\SimpleValue;
use Runn\ValueObjects\SimpleValueObject;
use Runn\ValueObjects\StringValue;
use Runn\ValueObjects\ValueObjectInterface;

class testClass extends SimpleValueObject {}

class SimpleValueObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyContsruct()
    {
        $valueObject = new testClass();
        $this->assertInstanceOf(SimpleValue::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
    }

    public function testNullConstruct()
    {
        $valueObject = new testClass(null);
        $this->assertInstanceOf(SimpleValue::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
    }

    public function testConstructPassThru()
    {
        $valueObject = new testClass('foo');

        $this->assertInstanceOf(testClass::class, $valueObject);
        $this->assertInstanceOf(SimpleValue::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertSame('foo', $valueObject->getValue());

        $reflect = new \ReflectionObject($valueObject);

        $validator = $reflect->getProperty('validator');
        $validator->setAccessible(true);
        $this->assertInstanceOf(Validator::class, $validator->getValue($valueObject));

        $sanitizer = $reflect->getProperty('sanitizer');
        $sanitizer->setAccessible(true);
        $this->assertInstanceOf(Sanitizer::class, $sanitizer->getValue($valueObject));
    }

    /**
     * @expectedException \Runn\Validation\ValidationError
     * @expectedExceptionMessage Some validation error
     */
    public function testConstructNormalValidatorFails()
    {
        $validator = new class extends Validator {
            public function validate($value): bool
            {
                throw new ValidationError($value, 'Some validation error');
            }
        };
        $valueObject = new testClass(42, $validator);
    }

    public function testConstructNormalValidatorSuccess()
    {
        $validator = new class extends Validator {
            public function validate($value): bool
            {
                if (42 == $value) {
                    return true;
                }
                throw new ValidationError($value, 'Some validation error');
            }
        };
        $valueObject = new testClass(42, $validator);

        $this->assertInstanceOf(testClass::class, $valueObject);
        $this->assertInstanceOf(SimpleValue::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertSame(42, $valueObject->getValue());
    }

    /**
     * @expectedException \Runn\Validation\ValidationError
     * @expectedExceptionMessage Value object validation error
     */
    public function testConstructAbormalValidatorFails()
    {
        $validator = new class extends Validator {
            public function validate($value): bool
            {
                if (42 == $value) {
                    return false;
                }
                throw new ValidationError($value, 'Some validation error');
            }
        };
        $valueObject = new testClass(42, $validator);
    }

    public function testConstructWithSanitizer()
    {
        $sanitizer = new class extends Sanitizer {
            public function sanitize($value)
            {
                return $value*2;
            }
        };

        $valueObject = new testClass(42, new PassThruValidator(), $sanitizer);
        $this->assertSame(84, $valueObject->getValue());
    }

    public function testIsEqual()
    {
        $value1 = new IntValue(42);
        $this->assertTrue($value1->isEqual($value1));

        $value2 = new StringValue(42);
        $this->assertFalse($value1->isEqual($value2));
        $this->assertFalse($value2->isEqual($value1));

        $value2 = new IntValue(24);
        $this->assertFalse($value1->isEqual($value2));
        $this->assertFalse($value2->isEqual($value1));

        $value2 = new IntValue(42);
        $this->assertTrue($value1->isEqual($value2));
        $this->assertTrue($value2->isEqual($value1));
    }

}
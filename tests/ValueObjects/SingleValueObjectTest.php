<?php

namespace Runn\tests\ValueObjects\SingleValueObject;

use PHPUnit\Framework\TestCase;
use Runn\Sanitization\Sanitizer;
use Runn\Validation\ValidationError;
use Runn\Validation\Validator;
use Runn\Validation\Validators\PassThruValidator;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\ValueObjectInterface;

class testClass extends SingleValueObject {}

class SingleValueObjectTest extends TestCase
{

    public function testEmptyContsruct()
    {
        $valueObject = new testClass();

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
        $this->assertSame('', (string)$valueObject);
    }

    public function testNullConstruct()
    {
        $valueObject = new testClass(null);

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
        $this->assertSame('', (string)$valueObject);
    }

    public function testConstructPassThru()
    {
        $valueObject = new testClass('foo');

        $this->assertInstanceOf(testClass::class, $valueObject);
        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertSame('foo', $valueObject->getValue());
        $this->assertSame('foo', (string)$valueObject);

        $reflect = new \ReflectionObject($valueObject);

        $validator = $reflect->getProperty('validator');
        $validator->setAccessible(true);
        $this->assertInstanceOf(Validator::class, $validator->getValue($valueObject));

        $sanitizer = $reflect->getProperty('sanitizer');
        $sanitizer->setAccessible(true);
        $this->assertInstanceOf(Sanitizer::class, $sanitizer->getValue($valueObject));
    }

    public function testConstructNormalValidatorFails()
    {
        $validator = new class extends Validator {
            public function validate($value): bool
            {
                throw new ValidationError($value, 'Some validation error');
            }
        };

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Some validation error');
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
        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertSame(42, $valueObject->getValue());
        $this->assertSame('42', (string)$valueObject);
    }

    public function testConstructAbnormalValidatorFails()
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

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage('Value object validation error');
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
        $this->assertSame('84', (string)$valueObject);
    }

    public function testJson()
    {
        $valueObject = new testClass(42);
        $this->assertSame('42', json_encode($valueObject));

        $valueObject = new testClass('foo');;
        $this->assertSame('"foo"', json_encode($valueObject));

        $valueObject = new testClass([1, 2, 3]);
        $this->assertSame('[1,2,3]', json_encode($valueObject));
    }

}

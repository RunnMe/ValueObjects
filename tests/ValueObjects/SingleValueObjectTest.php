<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\Sanitization\Sanitizer;
use Runn\Validation\ValidationError;
use Runn\Validation\Validator;
use Runn\Validation\Validators\PassThruValidator;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\ValueObjectInterface;

/**
 * Class SingleValueObjectTestClass
 * @package Runn\Tests\ValueObjects
 */
class SingleValueObjectTestClass extends SingleValueObject
{
}

/**
 * Class SingleValueObjectTest
 * @package Runn\Tests\ValueObjects
 */
class SingleValueObjectTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testEmptyContsruct(): void
    {
        $valueObject = new SingleValueObjectTestClass();

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
        $this->assertSame('', (string)$valueObject);
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testNullConstruct(): void
    {
        $valueObject = new SingleValueObjectTestClass(null);

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
        $this->assertSame('', (string)$valueObject);
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testConstructPassThru(): void
    {
        $valueObject = new SingleValueObjectTestClass('foo');

        $this->assertInstanceOf(SingleValueObjectTestClass::class, $valueObject);
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

    /**
     * @expectedException \Runn\Validation\ValidationError
     * @expectedExceptionMessage Some validation error
     */
    public function testConstructNormalValidatorFails(): void
    {
        $validator = new class extends Validator
        {
            public function validate($value): bool
            {
                throw new ValidationError($value, 'Some validation error');
            }
        };
        new SingleValueObjectTestClass(42, $validator);
    }

    /**
     * @throws ValidationError
     */
    public function testConstructNormalValidatorSuccess(): void
    {
        $validator = new class extends Validator
        {
            public function validate($value): bool
            {
                if (42 === $value) {
                    return true;
                }
                throw new ValidationError($value, 'Some validation error');
            }
        };
        $valueObject = new SingleValueObjectTestClass(42, $validator);

        $this->assertInstanceOf(SingleValueObjectTestClass::class, $valueObject);
        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(ValueObjectInterface::class, $valueObject);
        $this->assertInstanceOf(\JsonSerializable::class, $valueObject);

        $this->assertSame(42, $valueObject->getValue());
        $this->assertSame('42', (string)$valueObject);
    }

    /**
     * @expectedException \Runn\Validation\ValidationError
     * @expectedExceptionMessage Value object validation error
     */
    public function testConstructAbnormalValidatorFails(): void
    {
        $validator = new class extends Validator
        {
            public function validate($value): bool
            {
                if (42 === $value) {
                    return false;
                }
                throw new ValidationError($value, 'Some validation error');
            }
        };
        new SingleValueObjectTestClass(42, $validator);
    }

    /**
     * @throws ValidationError
     */
    public function testConstructWithSanitizer(): void
    {
        $sanitizer = new class extends Sanitizer
        {
            public function sanitize($value)
            {
                return $value * 2;
            }
        };

        $valueObject = new SingleValueObjectTestClass(42, new PassThruValidator(), $sanitizer);
        $this->assertSame(84, $valueObject->getValue());
        $this->assertSame('84', (string)$valueObject);
    }

    /**
     * @throws ValidationError
     */
    public function testJson(): void
    {
        $valueObject = new SingleValueObjectTestClass(42);
        $this->assertSame('42', json_encode($valueObject));

        $valueObject = new SingleValueObjectTestClass('foo');
        $this->assertSame('"foo"', json_encode($valueObject));

        $valueObject = new SingleValueObjectTestClass([1, 2, 3]);
        $this->assertSame('[1,2,3]', json_encode($valueObject));
    }
}

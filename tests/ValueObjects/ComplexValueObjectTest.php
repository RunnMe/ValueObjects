<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\Core\ObjectAsArrayInterface;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\EmptyFieldClass;
use Runn\ValueObjects\Errors\InvalidField;
use Runn\ValueObjects\Errors\InvalidFieldClass;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Errors\MissingField;
use Runn\ValueObjects\ValueObjectInterface;
use Runn\ValueObjects\Values\DateTimeValue;
use Runn\ValueObjects\Values\DateValue;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class testComplexValueObject
 * @package Runn\Tests\ValueObjects
 */
class TestComplexValueObject extends ComplexValueObject
{
    /**
     * @var array
     */
    protected static $schema = [
        'foo' => ['class' => IntValue::class]
    ];
}

/**
 * Class ComplexValueObjectTest
 * @package Runn\Tests\ValueObjects
 */
class ComplexValueObjectTest extends TestCase
{
    public function testEmptyComplexObjectEmptyData(): void
    {
        $object = new class extends ComplexValueObject
        {
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(0, $object);

        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame([], $class::getSchema());
        $this->assertSame([], $class::getFieldsList());
    }

    public function testEmptyComplexObjectInvalidSkippedKey(): void
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject
        {
        };
        $this->assertCount(0, $object);
    }

    public function testEmptyComplexObjectInvalidNotSkippedKey(): void
    {
        try {
            new class(['foo' => 42]) extends ComplexValueObject
            {
                protected const SKIP_EXCESS_FIELDS = false;
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            /** @var InvalidField[] $errors */
            $this->assertInstanceOf(InvalidField::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());
            $this->assertSame('Invalid complex value object field key: "foo"', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testComplexObjectMissingField(): void
    {
        try {
            new class extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['class' => IntValue::class],
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            /** @var InvalidField[] $errors */
            $this->assertInstanceOf(MissingField::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());
            $this->assertSame('Missing complex value object field "foo"', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidConstructOneField(): void
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(1, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo'], $class::getFieldsList());

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $object = new class(['foo' => new IntValue(42)]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(1, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo'], $class::getFieldsList());

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidConstructManyFields(): void
    {
        $object = new class(['foo' => 42, 'bar' => 'baz']) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(2, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo', 'bar'], $class::getFieldsList());

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertSame('baz', $object->bar);
        $this->assertInstanceOf(StringValue::class, $object->getObject('bar'));
        $this->assertEquals(new StringValue('baz'), $object->getObject('bar'));

        $object = new class(['foo' => new IntValue(42), 'bar' => new StringValue('baz')]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(2, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo', 'bar'], $class::getFieldsList());

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertSame('baz', $object->bar);
        $this->assertInstanceOf(StringValue::class, $object->getObject('bar'));
        $this->assertEquals(new StringValue('baz'), $object->getObject('bar'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidConstructWithDefault(): void
    {
        $object = new class(['bar' => 'baz']) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class, 'default' => 42],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(2, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo', 'bar'], $class::getFieldsList());

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertSame('baz', $object->bar);
        $this->assertInstanceOf(StringValue::class, $object->getObject('bar'));
        $this->assertEquals(new StringValue('baz'), $object->getObject('bar'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidConstructWithDefaultNull(): void
    {
        $object = new class(['bar' => 'baz']) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class, 'default' => null],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(2, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo', 'bar'], $class::getFieldsList());

        $this->assertNull($object->foo);

        $this->assertSame('baz', $object->bar);
        $this->assertInstanceOf(StringValue::class, $object->getObject('bar'));
        $this->assertEquals(new StringValue('baz'), $object->getObject('bar'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidConstructWithDefaultValue(): void
    {
        $object = new class(['foo' => null, 'bar' => 'baz']) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class, 'default' => null],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertCount(2, $object);
        /** @var ComplexValueObject $class */
        $class = \get_class($object);
        $this->assertSame(['foo', 'bar'], $class::getFieldsList());

        $this->assertNull($object->foo);

        $this->assertSame('baz', $object->bar);
        $this->assertInstanceOf(StringValue::class, $object->getObject('bar'));
        $this->assertEquals(new StringValue('baz'), $object->getObject('bar'));
    }

    public function testValidConstructWithoutDefault(): void
    {
        try {
            new class(['bar' => 'baz']) extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['class' => IntValue::class],
                    'bar' => ['class' => StringValue::class],
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(1, $errors);

            foreach ($errors as $error) {
                /** @var MissingField $error */
                $this->assertInstanceOf(MissingField::class, $error);
                $this->assertSame('foo', $error->getField());
                $this->assertSame('Missing complex value object field "foo"', $error->getMessage());
            }

            return;
        }
        $this->fail();
    }

    public function testInvalidFieldSkippedConstruct(): void
    {
        try {
            new class(['baz' => 'blablabla']) extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['class' => IntValue::class],
                    'bar' => ['class' => StringValue::class],
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            /** @var MissingField[] $errors */
            $this->assertInstanceOf(MissingField::class, $errors[0]);
            $this->assertContains('foo', $errors[0]->getField());
            $this->assertSame('Missing complex value object field "foo"', $errors[0]->getMessage());

            $this->assertInstanceOf(MissingField::class, $errors[1]);
            $this->assertSame('bar', $errors[1]->getField());
            $this->assertSame('Missing complex value object field "bar"', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testInvalidFieldNotSkippedConstruct(): void
    {
        try {
            new class(['baz' => 'blablabla']) extends ComplexValueObject
            {
                protected const SKIP_EXCESS_FIELDS = false;
                protected static $schema = [
                    'foo' => ['class' => IntValue::class],
                    'bar' => ['class' => StringValue::class],
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(3, $errors);

            /** @var InvalidField[]|MissingField[] $errors */
            $this->assertInstanceOf(InvalidField::class, $errors[0]);
            $this->assertSame('baz', $errors[0]->getField());
            $this->assertSame('Invalid complex value object field key: "baz"', $errors[0]->getMessage());

            $this->assertInstanceOf(MissingField::class, $errors[1]);
            $this->assertSame('foo', $errors[1]->getField());
            $this->assertSame('Missing complex value object field "foo"', $errors[1]->getMessage());

            $this->assertInstanceOf(MissingField::class, $errors[2]);
            $this->assertSame('bar', $errors[2]->getField());
            $this->assertSame('Missing complex value object field "bar"', $errors[2]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testEmptyFieldClassConstruct(): void
    {
        try {
            new class(['foo' => 42]) extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['wtf' => IntValue::class]
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            /** @var EmptyFieldClass[]|MissingField[] $errors */
            $this->assertInstanceOf(EmptyFieldClass::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());
            $this->assertSame('Empty complex value object field "foo" class', $errors[0]->getMessage());

            $this->assertInstanceOf(MissingField::class, $errors[1]);
            $this->assertSame('foo', $errors[1]->getField());
            $this->assertSame('Missing complex value object field "foo"', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    public function testInvalidFieldValueConstruct(): void
    {
        try {
            new class(['foo' => 'blablabla']) extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['class' => IntValue::class],
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            /** @var InvalidFieldValue[] $errors */
            $this->assertInstanceOf(InvalidFieldValue::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());
            $this->assertSame('blablabla', $errors[0]->getValue());
            $this->assertSame('Invalid complex value object field "foo" value', $errors[0]->getMessage());

            return;
        }
        $this->fail();
    }

    /*
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object field "foo" class
     */
    public function testInvalidFieldClassConstruct(): void
    {
        try {
            new class(['foo' => 42]) extends ComplexValueObject
            {
                protected static $schema = [
                    'foo' => ['class' => \stdClass::class]
                ];
            };
        } catch (ComplexValueObjectErrors $errors) {
            $this->assertCount(2, $errors);

            /** @var MissingField[]|InvalidFieldClass[] $errors */
            $this->assertInstanceOf(InvalidFieldClass::class, $errors[0]);
            $this->assertSame('foo', $errors[0]->getField());
            $this->assertSame(\stdClass::class, $errors[0]->getClass());
            $this->assertSame('Invalid complex value object field "foo" class', $errors[0]->getMessage());

            $this->assertInstanceOf(MissingField::class, $errors[1]);
            $this->assertSame('foo', $errors[1]->getField());
            $this->assertSame('Missing complex value object field "foo"', $errors[1]->getMessage());

            return;
        }
        $this->fail();
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Can not set field "foo" value because of value object is constructed
     * @throws ComplexValueObjectErrors
     * @throws \Runn\Validation\ValidationError
     * @throws \Runn\ValueObjects\Exception
     */
    public function testImmutable(): void
    {
        $object = new TestComplexValueObject(['foo' => 42]);

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $object->foo = 13;
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testGetValue(): void
    {
        $object = new class(['value' => 42]) extends ComplexValueObject
        {
            protected static $schema = [
                'value' => ['class' => IntValue::class]
            ];
        };

        $this->assertSame(42, $object->value);
        $this->assertInstanceOf(IntValue::class, $object->getObject('value'));
        $this->assertEquals(new IntValue(42), $object->getObject('value'));

        $this->assertEquals(['value' => 42], $object->getValue());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testGetObject(): void
    {
        $object = new class(['object' => 42]) extends ComplexValueObject
        {
            protected static $schema = [
                'object' => ['class' => IntValue::class]
            ];
        };

        $this->assertSame(42, $object->object);
        $this->assertInstanceOf(IntValue::class, $object->getObject('object'));
        $this->assertEquals(new IntValue(42), $object->getObject('object'));

        $this->assertEquals(['object' => 42], $object->getValue());
    }

    /**
     * @throws ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testIsSame(): void
    {
        $object1 = new TestComplexValueObject(['foo' => 42]);
        $this->assertTrue($object1->isSame($object1));

        $object2 = new class(['foo' => 42]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };
        $this->assertFalse($object1->isSame($object2));
        $this->assertFalse($object2->isSame($object1));

        $object2 = new TestComplexValueObject(['foo' => 24]);
        $this->assertFalse($object1->isSame($object2));
        $this->assertFalse($object2->isSame($object1));

        $object2 = new TestComplexValueObject(['foo' => 42]);
        $this->assertTrue($object1->isSame($object2));
        $this->assertTrue($object2->isSame($object1));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testJson(): void
    {
        $object = new class extends ComplexValueObject
        {
        };
        $this->assertSame('{}', json_encode($object));

        $object = new class([
            'foo' => new IntValue(42),
            'bar' => new StringValue('baz')
        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };
        $this->assertSame('{"foo":42,"bar":"baz"}', json_encode($object));

        $object = new class(['foo' => new IntValue(42)]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class, 'default' => null],
            ];
        };
        $this->assertSame('{"foo":42}', json_encode($object));
    }

    public function testJsonWithJsonSerializable(): void
    {
        $object = new class([
            'foo' => '2010-01-01',
            'bar' => '2010-01-02 12:01:02'
        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => DateValue::class],
                'bar' => ['class' => DateTimeValue::class],
            ];
        };
        $this->assertSame('{"foo":"2010-01-01","bar":"2010-01-02T12:01:02' . date('P') . '"}', json_encode($object));
    }
}

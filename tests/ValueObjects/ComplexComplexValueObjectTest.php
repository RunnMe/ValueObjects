<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\Core\CollectionInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\TypedCollection;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\ValueObjectInterface;
use Runn\ValueObjects\ValueObjectsCollection;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class InnerComplexValueObject
 * @package Runn\Tests\ValueObjects
 */
class InnerComplexValueObject extends ComplexValueObject
{
    protected static $schema = [
        'baz' => ['class' => StringValue::class],
    ];
}

/**
 * Class NullableInnerComplexValueObject
 * @package Runn\Tests\ValueObjects
 */
class NullableInnerComplexValueObject extends ComplexValueObject
{
    protected static $schema = [
        'baz' => ['class' => StringValue::class, 'default' => null],
    ];
}

/**
 * Class ComplexComplexValueObjectTest
 * @package Runn\Tests\ValueObjects
 */
class ComplexComplexValueObjectTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testValidSubComplex(): void
    {
        $object = new class([
            'foo' => 42,
            'bar' => new InnerComplexValueObject(['baz' => 'blabla'])
        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => InnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => 'blabla']], $object->getValue());

        $this->assertCount(2, $object);

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(InnerComplexValueObject::class, $object->bar);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object->bar);
        $this->assertInstanceOf(ValueObjectInterface::class, $object->bar);

        $this->assertSame('blabla', $object->bar->baz);
        $this->assertInstanceOf(StringValue::class, $object->bar->getObject('baz'));
        $this->assertEquals(new StringValue('blabla'), $object->bar->getObject('baz'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidCastSubComplex(): void
    {
        $object = new class([

            'foo' => 42,
            'bar' => ['baz' => 'blabla']

        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => InnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => 'blabla']], $object->getValue());

        $this->assertCount(2, $object);

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(InnerComplexValueObject::class, $object->bar);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object->bar);
        $this->assertInstanceOf(ValueObjectInterface::class, $object->bar);

        $this->assertSame('blabla', $object->bar->baz);
        $this->assertInstanceOf(StringValue::class, $object->bar->getObject('baz'));
        $this->assertEquals(new StringValue('blabla'), $object->bar->getObject('baz'));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValidCollection(): void
    {
        $object = new class([
            'foo' => 42,
            'bar' => new class([
                new IntValue(1),
                new IntValue(2),
                new IntValue(3)
            ]) extends ValueObjectsCollection
            {
            }
        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => ValueObjectsCollection::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => [1, 2, 3]], $object->getValue());

        $this->assertCount(2, $object);

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(CollectionInterface::class, $object->bar);
        $this->assertInstanceOf(TypedCollection::class, $object->bar);

        $this->assertEquals([new IntValue(1), new IntValue(2), new IntValue(3)], $object->bar->toArray());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testJsonEncode(): void
    {
        $object = new class([

            'foo' => 42,
            'bar' => ['baz' => null]

        ]) extends ComplexValueObject
        {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => NullableInnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => null]], $object->getValue());

        $this->assertCount(2, $object);

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(NullableInnerComplexValueObject::class, $object->bar);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object->bar);
        $this->assertInstanceOf(ValueObjectInterface::class, $object->bar);

        $this->assertNull($object->bar->baz);

        $this->assertSame(
            'null',
            json_encode($object->bar->baz)
        );
        $this->assertSame(
            '{}',
            json_encode($object->bar, JSON_FORCE_OBJECT)
        );
        $this->assertSame(
            '{"foo":42,"bar":{}}',
            json_encode($object, JSON_FORCE_OBJECT)
        );
    }
}

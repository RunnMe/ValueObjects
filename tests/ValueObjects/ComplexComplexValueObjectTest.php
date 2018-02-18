<?php

namespace Runn\tests\ValueObjects\ComplexValueObject;

use Runn\Core\CollectionInterface;
use Runn\Core\ObjectAsArrayInterface;
use Runn\Core\TypedCollection;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\ValueObjectInterface;
use Runn\ValueObjects\ValueObjectsCollection;

class InnerComplexValueObject extends ComplexValueObject {
    protected static $schema = [
        'baz' => ['class' => StringValue::class],
    ];
}

class NullableInnerComplexValueObject extends ComplexValueObject {
    protected static $schema = [
        'baz' => ['class' => StringValue::class, 'default' => null],
    ];
}

class ComplexComplexValueObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testValidSubComplex()
    {
        $object = new class([
            'foo' => 42,
            'bar' => new InnerComplexValueObject(['baz' => 'blabla'])
        ]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => InnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => 'blabla']], $object->getValue());

        $this->assertEquals(2, count($object));

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

    public function testValidCastSubComplex()
    {
        $object = new class([

            'foo' => 42,
            'bar' => ['baz' => 'blabla']

        ]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => InnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => 'blabla']], $object->getValue());

        $this->assertEquals(2, count($object));

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

    public function testValidCollection()
    {
        $object = new class([
            'foo' => 42,
            'bar' => new class([new IntValue(1), new IntValue(2), new IntValue(3)]) extends ValueObjectsCollection {}
        ]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => ValueObjectsCollection::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => [1, 2, 3]], $object->getValue());

        $this->assertEquals(2, count($object));

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(CollectionInterface::class, $object->bar);
        $this->assertInstanceOf(TypedCollection::class, $object->bar);

        $this->assertEquals([new IntValue(1), new IntValue(2), new IntValue(3)], $object->bar->toArray());
    }

    public function testJsonEncode()
    {
        $object = new class([

            'foo' => 42,
            'bar' => ['baz' => null]

        ]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => NullableInnerComplexValueObject::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);

        $this->assertSame(['foo' => 42, 'bar' => ['baz' => null]], $object->getValue());

        $this->assertEquals(2, count($object));

        $this->assertSame(42, $object->foo);
        $this->assertInstanceOf(IntValue::class, $object->getObject('foo'));
        $this->assertEquals(new IntValue(42), $object->getObject('foo'));

        $this->assertInstanceOf(NullableInnerComplexValueObject::class, $object->bar);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object->bar);
        $this->assertInstanceOf(ValueObjectInterface::class, $object->bar);

        $this->assertSame(null, $object->bar->baz);

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
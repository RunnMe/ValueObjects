<?php

namespace Runn\tests\ValueObjects\ValueObjectsCollection;

use Runn\Core\CollectionInterface;
use Runn\Core\TypedCollection;
use Runn\Core\TypedCollectionInterface;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\ValueObjectsCollection;

class testValueObjectsCollection extends ValueObjectsCollection {
    public static function getType()
    {
        return IntValue::class;
    }
};

class ValueObjectsCollectionTest
    extends \PHPUnit_Framework_TestCase
{

    public function testValid()
    {
        $collection = new class([
            new StringValue('foo'),
            new StringValue('bar'),
            new IntValue(42),
        ]) extends ValueObjectsCollection {};

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertCount(3, $collection);

        $this->assertSame(['foo', 'bar', 42], $collection->getValue());

        $this->assertSame('foo', $collection[0]->getValue());
        $this->assertSame('bar', $collection[1]->getValue());
        $this->assertSame(42,    $collection[2]->getValue());
    }

    public function testStrong()
    {
        $collection = new class([
            new IntValue(1),
            new IntValue(2),
            new IntValue(3),
        ]) extends ValueObjectsCollection {
            public static function getType()
            {
                return IntValue::class;
            }
        };

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertSame([1, 2, 3], $collection->getValue());

        $this->assertCount(3, $collection);

        $this->assertSame(1, $collection[0]->getValue());
        $this->assertSame(2, $collection[1]->getValue());
        $this->assertSame(3, $collection[2]->getValue());
    }

    public function testCastToType()
    {
        $collection = new class([
            1,
            2,
            3,
        ]) extends ValueObjectsCollection {
            public static function getType()
            {
                return IntValue::class;
            }
        };

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertCount(3, $collection);

        $this->assertSame(1, $collection[0]->getValue());
        $this->assertSame(2, $collection[1]->getValue());
        $this->assertSame(3, $collection[2]->getValue());
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalid()
    {
        $collection = new class([
            new IntValue(1),
            new IntValue(2),
            new StringValue('foo'),
        ]) extends ValueObjectsCollection {
            public static function getType()
            {
                return IntValue::class;
            }
        };
    }

    public function testIsSame()
    {
        $collection1 = new testValueObjectsCollection([1, 2, 3]);
        $this->assertTrue($collection1->isSame($collection1));

        $collection2 = new class([1, 2, 3]) extends ValueObjectsCollection {
            public static function getType()
            {
                return IntValue::class;
            }
        };

        $this->assertFalse($collection1->isSame($collection2));
        $this->assertFalse($collection2->isSame($collection1));

        $collection2 = new testValueObjectsCollection([1, 2]);
        $this->assertFalse($collection1->isSame($collection2));
        $this->assertFalse($collection2->isSame($collection1));

        $collection2 = new testValueObjectsCollection([1, 2, 3]);
        $this->assertTrue($collection1->isSame($collection2));
        $this->assertTrue($collection2->isSame($collection1));
    }

}
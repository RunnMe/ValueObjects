<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\Core\CollectionInterface;
use Runn\Core\TypedCollection;
use Runn\Core\TypedCollectionInterface;
use Runn\ValueObjects\ValueObjectsCollection;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class ValueObjectsCollectionTestClass
 * @package Runn\Tests\ValueObjects
 */
class ValueObjectsCollectionTestClass extends ValueObjectsCollection
{
    /**
     * @return string
     */
    public static function getType(): string
    {
        return IntValue::class;
    }
}

/**
 * Class ValueObjectsCollectionTest
 * @package Runn\Tests\ValueObjects
 */
class ValueObjectsCollectionTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testValid(): void
    {
        $collection = new class([
            new StringValue('foo'),
            new StringValue('bar'),
            new IntValue(42),
        ]) extends ValueObjectsCollection
        {
        };

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertCount(3, $collection);
        $this->assertSame(['foo', 'bar', 42], $collection->getValue());

        /** @var ValueObjectsCollection[] $collection */
        $this->assertSame('foo', $collection[0]->getValue());
        $this->assertSame('bar', $collection[1]->getValue());
        $this->assertSame(42, $collection[2]->getValue());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testStrong(): void
    {
        $collection = new class([
            new IntValue(1),
            new IntValue(2),
            new IntValue(3),
        ]) extends ValueObjectsCollection
        {
            /**
             * @return string
             */
            public static function getType(): string
            {
                return IntValue::class;
            }
        };

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertCount(3, $collection);
        $this->assertSame([1, 2, 3], $collection->getValue());

        /** @var ValueObjectsCollection[] $collection */
        $this->assertSame(1, $collection[0]->getValue());
        $this->assertSame(2, $collection[1]->getValue());
        $this->assertSame(3, $collection[2]->getValue());
    }

    public function testCastToType(): void
    {
        $collection = new class([
            1,
            2,
            3,
        ]) extends ValueObjectsCollection
        {
            /**
             * @return string
             */
            public static function getType(): string
            {
                return IntValue::class;
            }
        };

        $this->assertInstanceOf(CollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollectionInterface::class, $collection);
        $this->assertInstanceOf(TypedCollection::class, $collection);

        $this->assertCount(3, $collection);

        /** @var ValueObjectsCollection[] $collection */
        $this->assertSame(1, $collection[0]->getValue());
        $this->assertSame(2, $collection[1]->getValue());
        $this->assertSame(3, $collection[2]->getValue());
    }

    /**
     * @expectedException \Runn\Core\Exception
     * @expectedExceptionMessage Typed collection type mismatch
     */
    public function testInvalid(): void
    {
        new class([
            new IntValue(1),
            new IntValue(2),
            new StringValue('foo'),
        ]) extends ValueObjectsCollection
        {
            /**
             * @return string
             */
            public static function getType(): string
            {
                return IntValue::class;
            }
        };
    }

    public function testIsSame(): void
    {
        $collection1 = new ValueObjectsCollectionTestClass([1, 2, 3]);
        $this->assertTrue($collection1->isSame($collection1));

        $collection2 = new class([1, 2, 3]) extends ValueObjectsCollection
        {
            /**
             * @return string
             */
            public static function getType(): string
            {
                return IntValue::class;
            }
        };

        $this->assertFalse($collection1->isSame($collection2));
        $this->assertFalse($collection2->isSame($collection1));

        $collection2 = new ValueObjectsCollectionTestClass([1, 2]);
        $this->assertFalse($collection1->isSame($collection2));
        $this->assertFalse($collection2->isSame($collection1));

        $collection2 = new ValueObjectsCollectionTestClass([1, 2, 3]);
        $this->assertTrue($collection1->isSame($collection2));
        $this->assertTrue($collection2->isSame($collection1));
    }
}

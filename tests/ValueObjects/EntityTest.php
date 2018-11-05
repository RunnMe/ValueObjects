<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\Core\Std;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Entity;
use Runn\ValueObjects\Values\BooleanValue;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class EntityTestClass
 * @package Runn\Tests\ValueObjects
 */
class EntityTestClass extends Entity
{
    /**
     * @var array
     */
    protected static $schema = [
        '__id' => ['class' => IntValue::class],
        'foo' => ['class' => StringValue::class]
    ];
}

/**
 * Class EntityTest
 * @package Runn\Tests\ValueObjects
 */
class EntityTest extends TestCase
{
    public function testPkFields(): void
    {
        $entity = new class extends Entity
        {
            protected const PK_FIELDS = [];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertInstanceOf(ComplexValueObject::class, $entity);

        $const = new \ReflectionClassConstant($class, 'PK_FIELDS');

        $this->assertSame($const->getValue(), $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsList());
        $this->assertSame([], $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity
        {
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $const = new \ReflectionClassConstant($class, 'PK_FIELDS');

        $this->assertSame($const->getValue(), $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsList());
        $this->assertSame(['__id'], $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['id'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $const = new \ReflectionClassConstant($class, 'PK_FIELDS');

        $this->assertSame($const->getValue(), $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsList());
        $this->assertSame(['id'], $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['foo', 'bar'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $const = new \ReflectionClassConstant($class, 'PK_FIELDS');

        $this->assertSame($const->getValue(), $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsList());
        $this->assertSame(['foo', 'bar'], $class::getPrimaryKeyFields());
        $this->assertSame([], $class::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());
    }

    public function testIsPrimaryKeyScalar(): void
    {
        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['id'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertTrue($class::isPrimaryKeyScalar());

        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['id1', 'id2'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertFalse($class::isPrimaryKeyScalar());
    }

    public function testGetPk(): void
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected const PK_FIELDS = [];
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertSame(['__id', 'foo'], $class::getFieldsList());
        $this->assertNull($entity->getPrimaryKey());
        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertSame(['__id', 'foo'], $class::getFieldsListWoPk());

        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertSame(['__id', 'foo'], $class::getFieldsList());
        $this->assertSame(1, $entity->getPrimaryKey());
        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(['foo'], $class::getFieldsListWoPk());

        $entity = new class() extends Entity
        {
            protected const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class, 'default' => null],
                'second' => ['class' => IntValue::class, 'default' => null],
                'foo' => ['class' => StringValue::class, 'default' => null],
            ];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertSame(['first', 'second', 'foo'], $class::getFieldsList());
        $this->assertNull($entity->getPrimaryKey());
        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertSame(['foo'], $class::getFieldsListWoPk());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity
        {
            protected const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertSame(['first', 'second', 'foo'], $class::getFieldsList());
        $this->assertSame(['first' => 1, 'second' => 2], $entity->getPrimaryKey());
        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(['foo'], $class::getFieldsListWoPk());
    }

    public function testGetValueWoPk(): void
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected const PK_FIELDS = [];
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['__id' => 1, 'foo' => 'bar'], $entity->getValueWithoutPrimaryKey());

        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['foo' => 'bar'], $entity->getValueWithoutPrimaryKey());

        $entity = new class() extends Entity
        {
            protected const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class, 'default' => null],
                'second' => ['class' => IntValue::class, 'default' => null],
                'foo' => ['class' => StringValue::class, 'default' => null],
            ];
        };
        $this->assertSame(['foo' => null], $entity->getValueWithoutPrimaryKey());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity
        {
            protected const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['foo' => 'bar'], $entity->getValueWithoutPrimaryKey());
    }

    /**
     * @throws \Runn\Core\Exceptions
     */
    public function testConformsPK(): void
    {
        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['id'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertTrue($class::conformsToPrimaryKey(null));
        $this->assertTrue($class::conformsToPrimaryKey(1));
        $this->assertTrue($class::conformsToPrimaryKey('foo'));
        $this->assertFalse($class::conformsToPrimaryKey([]));
        $this->assertTrue($class::conformsToPrimaryKey(['id' => 1]));
        $this->assertTrue($class::conformsToPrimaryKey(new Std(['id' => 1])));
        $this->assertFalse($class::conformsToPrimaryKey(['id' => 1, 'foo' => 'bar']));
        $this->assertFalse($class::conformsToPrimaryKey(new Std(['id' => 1, 'foo' => 'bar'])));

        $entity = new class extends Entity
        {
            protected const PK_FIELDS = ['id1', 'id2'];
        };

        /** @var Entity $class */
        $class = \get_class($entity);

        $this->assertTrue($class::conformsToPrimaryKey(null));
        $this->assertFalse($class::conformsToPrimaryKey(1));
        $this->assertFalse($class::conformsToPrimaryKey('foo'));
        $this->assertFalse($class::conformsToPrimaryKey([]));
        $this->assertFalse($class::conformsToPrimaryKey(['id1' => 1]));
        $this->assertFalse($class::conformsToPrimaryKey(new Std(['id1' => 1])));
        $this->assertTrue($class::conformsToPrimaryKey(['id1' => 1, 'id2' => 2]));
        $this->assertTrue($class::conformsToPrimaryKey(new Std(['id1' => 1, 'id2' => 2])));
        $this->assertFalse($class::conformsToPrimaryKey(['id1' => 1, 'id2' => 2, 'id3' => 3]));
        $this->assertFalse($class::conformsToPrimaryKey(new Std(['id1' => 1, 'id2' => 2, 'id3' => 3])));
    }

    /**
     * @throws \Runn\Validation\ValidationError
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testIsSame(): void
    {
        $entity1 = new EntityTestClass(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isSame($entity1));

        $entity2 = new BooleanValue(true);
        $this->assertFalse($entity1->isSame($entity2));

        $entity2 = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };
        $this->assertFalse($entity1->isSame($entity2));
        $this->assertFalse($entity2->isSame($entity1));

        $entity2 = new EntityTestClass(['__id' => 2, 'foo' => 'bar']);
        $this->assertFalse($entity1->isSame($entity2));
        $this->assertFalse($entity2->isSame($entity1));

        $entity2 = new EntityTestClass(['__id' => 1, 'foo' => 'baz']);
        $this->assertTrue($entity1->isSame($entity2));
        $this->assertTrue($entity2->isSame($entity1));

        $entity2 = new EntityTestClass(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isSame($entity2));
        $this->assertTrue($entity2->isSame($entity1));
    }

    /**
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testIsEqual(): void
    {
        $entity1 = new EntityTestClass(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity1));

        $entity2 = new class(['__id' => 1, 'foo' => 'bar']) extends Entity
        {
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo' => ['class' => StringValue::class],
            ];
        };
        $this->assertFalse($entity1->isEqual($entity2));
        $this->assertFalse($entity2->isEqual($entity1));

        $entity2 = new EntityTestClass(['__id' => 2, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));

        $entity2 = new EntityTestClass(['__id' => 1, 'foo' => 'baz']);
        $this->assertFalse($entity1->isEqual($entity2));
        $this->assertFalse($entity2->isEqual($entity1));

        $entity2 = new EntityTestClass(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Can not set field "__id" value because of it is part of primary key
     * @throws \Runn\Validation\ValidationError
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testImmutablePk(): void
    {
        $entity = new EntityTestClass(['__id' => 42, 'foo' => 'bar']);

        $this->assertSame(42, $entity->getPrimaryKey());
        $this->assertSame(42, $entity->__id);
        $this->assertInstanceOf(IntValue::class, $entity->getObject('__id'));
        $this->assertEquals(new IntValue(42), $entity->getObject('__id'));

        $this->assertSame('bar', $entity->foo);
        $this->assertInstanceOf(StringValue::class, $entity->getObject('foo'));
        $this->assertEquals(new StringValue('bar'), $entity->getObject('foo'));

        $entity->__id = 13;
    }

    /**
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testMutablePk(): void
    {
        $entity = new EntityTestClass(['foo' => 'bar']);

        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertNull($entity->getPrimaryKey());

        $entity->__id = 13;

        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(13, $entity->getPrimaryKey());
    }

    /**
     * @throws \Runn\Validation\ValidationError
     * @throws \Runn\ValueObjects\Errors\ComplexValueObjectErrors
     * @throws \Runn\ValueObjects\Exception
     */
    public function testMutableField(): void
    {
        $entity = new EntityTestClass(['__id' => 42, 'foo' => 'bar']);

        $this->assertSame(42, $entity->getPrimaryKey());
        $this->assertSame(42, $entity->__id);
        $this->assertInstanceOf(IntValue::class, $entity->getObject('__id'));
        $this->assertEquals(new IntValue(42), $entity->getObject('__id'));

        $this->assertSame('bar', $entity->foo);
        $this->assertInstanceOf(StringValue::class, $entity->getObject('foo'));
        $this->assertEquals(new StringValue('bar'), $entity->getObject('foo'));

        $entity->foo = new StringValue('baz');
        $this->assertSame('baz', $entity->foo);
        $this->assertInstanceOf(StringValue::class, $entity->getObject('foo'));
        $this->assertEquals(new StringValue('baz'), $entity->getObject('foo'));

        $entity->foo = 'bla';
        $this->assertSame('bla', $entity->foo);
        $this->assertInstanceOf(StringValue::class, $entity->getObject('foo'));
        $this->assertEquals(new StringValue('bla'), $entity->getObject('foo'));
    }
}

<?php

namespace Runn\tests\ValueObjects\Entity;

use Runn\Core\Std;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Entity;
use Runn\ValueObjects\Values\BooleanValue;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

class testEntity extends Entity{
    protected static $schema = [
        '__id' => ['class' => IntValue::class],
        'foo' =>  ['class' => StringValue::class]
    ];
}

class testValueObject1 extends ComplexValueObject {
    protected static $schema = [
        'foo' =>  ['class' => StringValue::class]
    ];
}

class testValueObject2 extends ComplexValueObject {
    protected static $schema = [
        'foo' =>  ['class' => StringValue::class]
    ];
}

class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function testPkFields()
    {
        $entity = new class extends Entity {const PK_FIELDS = [];};

        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertInstanceOf(ComplexValueObject::class, $entity);

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsList());
        $this->assertSame([], get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity {};

        $this->assertSame([], get_class($entity)::getFieldsList());
        $this->assertSame(['__id'], get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity {const PK_FIELDS = ['id'];};

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsList());
        $this->assertSame(['id'], get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());

        $entity = new class extends Entity {const PK_FIELDS = ['foo', 'bar'];};

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsList());
        $this->assertSame(['foo', 'bar'], get_class($entity)::getPrimaryKeyFields());
        $this->assertSame([], get_class($entity)::getFieldsListWoPk());
        $this->assertFalse($entity->issetPrimaryKey());
    }

    public function testIsPrimaryKeyScalar()
    {
        $entity = new class extends Entity {const PK_FIELDS = ['id'];};
        $class = get_class($entity);
        $this->assertTrue($class::isPrimaryKeyScalar());

        $entity = new class extends Entity {const PK_FIELDS = ['id1', 'id2'];};
        $class = get_class($entity);
        $this->assertFalse($class::isPrimaryKeyScalar());

    }

    public function testGetPk()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = [];
            protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(['__id', 'foo'], get_class($entity)::getFieldsList());
        $this->assertSame(null, $entity->getPrimaryKey());
        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertSame(['__id', 'foo'], get_class($entity)::getFieldsListWoPk());

        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(['__id', 'foo'], get_class($entity)::getFieldsList());
        $this->assertSame(1, $entity->getPrimaryKey());
        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(['foo'], get_class($entity)::getFieldsListWoPk());

        $entity = new class() extends Entity {
            const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class, 'default' => null],
                'second' => ['class' => IntValue::class, 'default' => null],
                'foo'  => ['class' => StringValue::class, 'default' => null],
            ];
        };
        $this->assertSame(['first', 'second', 'foo'], get_class($entity)::getFieldsList());
        $this->assertSame(null, $entity->getPrimaryKey());
        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertSame(['foo'], get_class($entity)::getFieldsListWoPk());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['first', 'second', 'foo'], get_class($entity)::getFieldsList());
        $this->assertSame(['first' => 1, 'second' => 2], $entity->getPrimaryKey());
        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(['foo'], get_class($entity)::getFieldsListWoPk());
    }

    public function testGetValueWoPk()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = [];
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];};
        $this->assertSame(['__id' => 1, 'foo' => 'bar'], $entity->getValueWithoutPrimaryKey());

        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(['foo' => 'bar'], $entity->getValueWithoutPrimaryKey());

        $entity = new class() extends Entity {
            const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class, 'default' => null],
                'second' => ['class' => IntValue::class, 'default' => null],
                'foo'  => ['class' => StringValue::class, 'default' => null],
            ];
        };
        $this->assertSame(['foo' => null], $entity->getValueWithoutPrimaryKey());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['foo' => 'bar'], $entity->getValueWithoutPrimaryKey());
    }

    public function testConformsPK()
    {
        $entity = new class extends Entity {const PK_FIELDS = ['id'];};
        $class = get_class($entity);

        $this->assertTrue($class::conformsToPrimaryKey(null));
        $this->assertTrue($class::conformsToPrimaryKey(1));
        $this->assertTrue($class::conformsToPrimaryKey('foo'));
        $this->assertFalse($class::conformsToPrimaryKey([]));
        $this->assertTrue($class::conformsToPrimaryKey(['id' => 1]));
        $this->assertTrue($class::conformsToPrimaryKey(new Std(['id' => 1])));
        $this->assertFalse($class::conformsToPrimaryKey(['id' => 1, 'foo' => 'bar']));
        $this->assertFalse($class::conformsToPrimaryKey(new Std(['id' => 1, 'foo' => 'bar'])));

        $entity = new class extends Entity {const PK_FIELDS = ['id1', 'id2'];};
        $class = get_class($entity);

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

    public function testIsSame()
    {
        $entity1 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isSame($entity1));

        $entity2 = new BooleanValue(true);
        $this->assertFalse($entity1->isSame($entity2));

        $entity2 = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertFalse($entity1->isSame($entity2));
        $this->assertFalse($entity2->isSame($entity1));

        $entity2 = new testEntity(['__id' => 2, 'foo' => 'bar']);
        $this->assertFalse($entity1->isSame($entity2));
        $this->assertFalse($entity2->isSame($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'baz']);
        $this->assertTrue($entity1->isSame($entity2));
        $this->assertTrue($entity2->isSame($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isSame($entity2));
        $this->assertTrue($entity2->isSame($entity1));
    }

    public function testIsEqual()
    {
        $entity1 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity1));

        $entity2 = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertFalse($entity1->isEqual($entity2));
        $this->assertFalse($entity2->isEqual($entity1));

        $entity2 = new testEntity(['__id' => 2, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'baz']);
        $this->assertFalse($entity1->isEqual($entity2));
        $this->assertFalse($entity2->isEqual($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Can not set field "__id" value because of it is part of primary key
     */
    public function testImmutablePk()
    {
        $entity = new testEntity(['__id' => 42, 'foo' => 'bar']);

        $this->assertSame(42, $entity->getPrimaryKey());
        $this->assertSame(42, $entity->__id);
        $this->assertInstanceOf(IntValue::class, $entity->getObject('__id'));
        $this->assertEquals(new IntValue(42), $entity->getObject('__id'));

        $this->assertSame('bar', $entity->foo);
        $this->assertInstanceOf(StringValue::class, $entity->getObject('foo'));
        $this->assertEquals(new StringValue('bar'), $entity->getObject('foo'));

        $entity->__id = 13;
    }

    public function testMutablePk()
    {
        $entity = new testEntity(['foo' => 'bar']);

        $this->assertFalse($entity->issetPrimaryKey());
        $this->assertNull($entity->getPrimaryKey());

        $entity->__id = 13;

        $this->assertTrue($entity->issetPrimaryKey());
        $this->assertSame(13, $entity->getPrimaryKey());
    }

    public function testMutableField()
    {
        $entity = new testEntity(['__id' => 42, 'foo' => 'bar']);

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
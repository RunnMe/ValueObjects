<?php

namespace Runn\tests\ValueObjects\Entity;

use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Entity;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;
use Runn\ValueObjects\ValueObjectInterface;

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
        $this->assertSame([], get_class($entity)::getPrimaryKeyFields());

        $entity = new class extends Entity {};

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame(['__id'], get_class($entity)::getPrimaryKeyFields());

        $entity = new class extends Entity {const PK_FIELDS = ['id'];};

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame(['id'], get_class($entity)::getPrimaryKeyFields());

        $entity = new class extends Entity {const PK_FIELDS = ['foo', 'bar'];};

        $this->assertSame(get_class($entity)::PK_FIELDS, get_class($entity)::getPrimaryKeyFields());
        $this->assertSame(['foo', 'bar'], get_class($entity)::getPrimaryKeyFields());
    }

    public function testGetPk()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = [];
            protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(null, $entity->getPrimaryKey());

        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(1, $entity->getPrimaryKey());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity {
            const PK_FIELDS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['first' => 1, 'second' => 2], $entity->getPrimaryKey());
    }

    public function testIsSame()
    {
        $entity1 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isSame($entity1));

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
        $this->assertFalse($entity1->isSame($entity2));
        $this->assertFalse($entity2->isSame($entity1));

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
        $this->assertFalse($entity1->isEqual($entity2));
        $this->assertFalse($entity2->isEqual($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'baz']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));

        $entity2 = new testEntity(['__id' => 1, 'foo' => 'bar']);
        $this->assertTrue($entity1->isEqual($entity2));
        $this->assertTrue($entity2->isEqual($entity1));
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Can not set field "__id" value because of value object is constructed
     */
    public function testImmutablePk()
    {
        $entity = new testEntity(['__id' => 42, 'foo' => 'bar']);

        $this->assertSame(42, $entity->getPrimaryKey());
        $this->assertSame(42, $entity->__id->getValue());
        $this->assertSame('bar', $entity->foo->getValue());

        $entity->__id = 13;
    }

}
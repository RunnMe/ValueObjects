<?php

namespace Runn\tests\ValueObjects\Entity;

use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Entity;
use Runn\ValueObjects\IntValue;
use Runn\ValueObjects\StringValue;
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

    public function testPkColumns()
    {
        $entity = new class extends Entity {};

        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertInstanceOf(ComplexValueObject::class, $entity);

        $this->assertSame(get_class($entity)::PK_COLUMNS, get_class($entity)::getPrimaryKeyColumns());
        $this->assertSame(['__id'], get_class($entity)::getPrimaryKeyColumns());

        $entity = new class extends Entity {const PK_COLUMNS = ['id'];};

        $this->assertSame(get_class($entity)::PK_COLUMNS, get_class($entity)::getPrimaryKeyColumns());
        $this->assertSame(['id'], get_class($entity)::getPrimaryKeyColumns());

        $entity = new class extends Entity {const PK_COLUMNS = ['foo', 'bar'];};

        $this->assertSame(get_class($entity)::PK_COLUMNS, get_class($entity)::getPrimaryKeyColumns());
        $this->assertSame(['foo', 'bar'], get_class($entity)::getPrimaryKeyColumns());
    }

    public function testGetPk()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};
        $this->assertSame(['__id' => 1], $entity->getPrimaryKey());

        $entity = new class(['first' => 1, 'second' => 2, 'foo' => 'bar']) extends Entity {
            const PK_COLUMNS = ['first', 'second'];
            protected static $schema = [
                'first' => ['class' => IntValue::class],
                'second' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];
        };
        $this->assertSame(['first' => 1, 'second' => 2], $entity->getPrimaryKey());
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
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object class
     */
    public function testToValueObjectInvalidClass()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};

        $object = $entity->toValueObject(\stdClass::class);
    }

    public function testToValueObject()
    {
        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};

        $object = $entity->toValueObject();

        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertInstanceOf(ComplexValueObject::class, $object);

        $this->assertSame(['foo' => ['class' => StringValue::class]], $object->getSchema());
        $this->assertSame(['foo' => 'bar'], $object->getValue());


        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity {
            protected static $schema = [
                '__id' => ['class' => IntValue::class],
                'foo'  => ['class' => StringValue::class],
            ];
            public static function getValueObjectClass()
            {return testValueObject1::class;}
        };

        $object = $entity->toValueObject();

        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(testValueObject1::class, $object);

        $this->assertSame(['foo' => ['class' => StringValue::class]], $object->getSchema());
        $this->assertSame(['foo' => 'bar'], $object->getValue());


        $entity = new class(['__id' => 1, 'foo' => 'bar']) extends Entity { protected static $schema = [
            '__id' => ['class' => IntValue::class],
            'foo'  => ['class' => StringValue::class],
        ];};

        $object = $entity->toValueObject(testValueObject2::class);

        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(testValueObject2::class, $object);

        $this->assertSame(['foo' => ['class' => StringValue::class]], $object->getSchema());
        $this->assertSame(['foo' => 'bar'], $object->getValue());
    }

    public function testFromValueObject()
    {
        $entity = testEntity::fromValueObject(new testValueObject1(['foo' => 'bar']), ['__id' => 1]);
        $this->assertInstanceOf(Entity::class, $entity);
        $this->assertInstanceOf(testEntity::class, $entity);
        $this->assertSame(['__id' => 1, 'foo' => 'bar'], $entity->getValue());
    }

}
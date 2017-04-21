<?php


namespace Runn\tests\ValueObjects\ComplexValueObject;

use Runn\Core\ObjectAsArrayInterface;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\IntValue;
use Runn\ValueObjects\StringValue;
use Runn\ValueObjects\ValueObjectInterface;

class testComplexValueObject extends ComplexValueObject {
    protected static $schema = [
        'foo' => ['class' => IntValue::class]
    ];
}

class ComplexValueObjectTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyComplexObjectEmptyData()
    {
        $object = new class extends ComplexValueObject {};
        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(0, count($object));
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object member "foo"
     */
    public function testEmptyComplexObjectInvalidKey()
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject {};
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Missing complex value object member "foo"
     */
    public function testComplexObjectMissingMember()
    {
        $object = new class extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };
    }

    public function testValidConstructOneMember()
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(1, count($object));
        $this->assertInstanceOf(IntValue::class, $object->foo);
        $this->assertSame(42, $object->foo->getValue());

        $object = new class(['foo' => new IntValue(42)]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(1, count($object));
        $this->assertInstanceOf(IntValue::class, $object->foo);
        $this->assertSame(42, $object->foo->getValue());
    }

    public function testValidConstructManyMembers()
    {
        $object = new class(['foo' => 42, 'bar' => 'baz']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(2, count($object));
        $this->assertInstanceOf(IntValue::class, $object->foo);
        $this->assertSame(42, $object->foo->getValue());
        $this->assertInstanceOf(StringValue::class, $object->bar);
        $this->assertSame('baz', $object->bar->getValue());

        $object = new class(['foo' => new IntValue(42), 'bar' => new StringValue('baz')]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(2, count($object));
        $this->assertInstanceOf(IntValue::class, $object->foo);
        $this->assertSame(42, $object->foo->getValue());
        $this->assertInstanceOf(StringValue::class, $object->bar);
        $this->assertSame('baz', $object->bar->getValue());
    }

    public function testValidConstructWithDefault()
    {
        $object = new class(['bar' => 'baz']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class, 'default' => 42],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(2, count($object));
        $this->assertInstanceOf(IntValue::class, $object->foo);
        $this->assertSame(42, $object->foo->getValue());
        $this->assertInstanceOf(StringValue::class, $object->bar);
        $this->assertSame('baz', $object->bar->getValue());
    }

    public function testValidConstructWithDefaultNull()
    {
        $object = new class(['bar' => 'baz']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class, 'default' => null],
                'bar' => ['class' => StringValue::class],
            ];
        };

        $this->assertInstanceOf(ComplexValueObject::class, $object);
        $this->assertInstanceOf(ObjectAsArrayInterface::class, $object);
        $this->assertInstanceOf(ValueObjectInterface::class, $object);
        $this->assertEquals(2, count($object));
        $this->assertNull($object->foo);
        $this->assertInstanceOf(StringValue::class, $object->bar);
        $this->assertSame('baz', $object->bar->getValue());
    }

    public function testValidConstructWithDefaultValue()
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
        $this->assertEquals(2, count($object));

        $this->assertNull($object->foo);
        $this->assertInstanceOf(StringValue::class, $object->bar);
        $this->assertSame('baz', $object->bar->getValue());
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Missing complex value object member "foo"
     */
    public function testValidConstructWithoutDefault()
    {
        $object = new class(['bar' => 'baz']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object member "baz"
     */
    public function testInvalidMemberConstruct()
    {
        $object = new class(['baz' => 'blablabla']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object member "baz"
     */
    public function testInvalidMemberSet()
    {
        $object = new class(['baz' => 'blablabla']) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Empty complex value object member "foo" class
     */
    public function testEmptyMemberClassConstruct()
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['wtf' => IntValue::class]
            ];
        };
    }

    /**
     * @expectedException \Runn\ValueObjects\Exception
     * @expectedExceptionMessage Invalid complex value object member "foo" class
     */
    public function testInvalidMemberClassConstruct()
    {
        $object = new class(['foo' => 42]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => \stdClass::class]
            ];
        };
    }

    public function testIsEqual()
    {
        $object1 = new testComplexValueObject(['foo' => 42]);
        $this->assertTrue($object1->isEqual($object1));

        $object2 = new class(['foo' => 42]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class]
            ];
        };
        $this->assertFalse($object1->isEqual($object2));
        $this->assertFalse($object2->isEqual($object1));

        $object2 = new testComplexValueObject(['foo' => 24]);
        $this->assertFalse($object1->isEqual($object2));
        $this->assertFalse($object2->isEqual($object1));

        $object2 = new testComplexValueObject(['foo' => 42]);
        $this->assertTrue($object1->isEqual($object2));
        $this->assertTrue($object2->isEqual($object1));
    }

    public function testJson()
    {
        $object = new class extends ComplexValueObject {};
        $this->assertSame('[]', json_encode($object));

        $object = new class(['foo' => new IntValue(42), 'bar' => new StringValue('baz')]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class],
            ];
        };
        $this->assertSame('{"foo":42,"bar":"baz"}', json_encode($object));

        $object = new class(['foo' => new IntValue(42)]) extends ComplexValueObject {
            protected static $schema = [
                'foo' => ['class' => IntValue::class],
                'bar' => ['class' => StringValue::class, 'default' => null],
            ];
        };
        $this->assertSame('{"foo":42}', json_encode($object));
    }

}
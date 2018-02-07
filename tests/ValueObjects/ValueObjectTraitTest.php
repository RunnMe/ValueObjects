<?php

namespace Runn\tests\ValueObjects\ValueObjectTrait;

use Runn\ValueObjects\ValueObjectInterface;
use Runn\ValueObjects\ValueObjectTrait;

class testClass implements ValueObjectInterface {
    use ValueObjectTrait;
}

class ValueObjectTraitTest
    extends \PHPUnit_Framework_TestCase
{

    public function testEmptyContsruct()
    {
        $obj = new class implements ValueObjectInterface {
            use ValueObjectTrait;
        };
        $this->assertNull($obj->getValue());
        $this->assertNull($obj());
        $this->assertSame('', (string)$obj);
    }

    public function testNullConstruct()
    {
        $obj = new class(null) implements ValueObjectInterface {
            use ValueObjectTrait;
        };
        $this->assertNull($obj->getValue());
        $this->assertNull($obj());
        $this->assertSame('', (string)$obj);
    }

    public function testConstructGetValue()
    {
        $obj = new class(42) implements ValueObjectInterface {
            use ValueObjectTrait;
        };
        $this->assertSame(42, $obj->getValue());
        $this->assertSame(42, $obj());
        $this->assertSame('42', (string)$obj);
    }

    public function testNewStatic()
    {
        $obj = testClass::new(12);

        $this->assertInstanceOf(testClass::class, $obj);
        $this->assertSame(12, $obj->getValue());
        $this->assertSame(12, $obj());
        $this->assertSame('12', (string)$obj);
    }

    public function testNewDynamic()
    {
        $obj1 = testClass::new('foo');
        $obj2 = $obj1->new('bar');

        $this->assertSame(get_class($obj2), get_class($obj1));
        $this->assertNotSame($obj2, $obj1);
        $this->assertNotEquals($obj2, $obj1);
    }

    public function testIsSame()
    {
        $obj1 = new class(24) implements ValueObjectInterface {
            use ValueObjectTrait;
        };

        $obj2 = new class(42) implements ValueObjectInterface {
            use ValueObjectTrait;
        };

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new class(42) implements ValueObjectInterface {
            use ValueObjectTrait;
        };

        $obj2 = new class(42) implements ValueObjectInterface {
            use ValueObjectTrait;
        };

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new testClass(12);
        $obj2 = new testClass(13);

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new testClass(13);
        $obj2 = new testClass(13);

        $this->assertTrue($obj1->isSame($obj2));
        $this->assertTrue($obj2->isSame($obj1));
    }

}
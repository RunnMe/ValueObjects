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

    public function testConstructGetValue()
    {
        $obj = new class(42) implements ValueObjectInterface {
            use ValueObjectTrait;
        };
        $this->assertSame(42, $obj->getValue());
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
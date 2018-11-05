<?php

namespace Runn\Tests\ValueObjects;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\ValueObjectInterface;
use Runn\ValueObjects\ValueObjectTrait;

class ValueObjectTraitTestClass implements ValueObjectInterface
{
    use ValueObjectTrait;
}

/**
 * Class ValueObjectTraitTest
 * @package Runn\Tests\ValueObjects
 */
class ValueObjectTraitTest extends TestCase
{
    public function testEmptyContsruct(): void
    {
        $obj = new class implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };
        $this->assertNull($obj->getValue());
        $this->assertNull($obj());
        $this->assertSame('', (string)$obj);
    }

    public function testNullConstruct(): void
    {
        $obj = new class(null) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };
        $this->assertNull($obj->getValue());
        $this->assertNull($obj());
        $this->assertSame('', (string)$obj);
    }

    public function testConstructGetValue(): void
    {
        $obj = new class(42) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };
        $this->assertSame(42, $obj->getValue());
        $this->assertSame(42, $obj());
        $this->assertSame('42', (string)$obj);
    }

    public function testNewStatic(): void
    {
        $obj = ValueObjectTraitTestClass::new(12);

        $this->assertInstanceOf(ValueObjectTraitTestClass::class, $obj);
        $this->assertSame(12, $obj->getValue());
        $this->assertSame(12, $obj());
        $this->assertSame('12', (string)$obj);
    }


    public function testNewDynamic(): void
    {
        $obj1 = ValueObjectTraitTestClass::new('foo');
        $obj2 = $obj1->new('bar');

        $this->assertSame(\get_class($obj2), \get_class($obj1));
        $this->assertNotSame($obj2, $obj1);
        $this->assertNotEquals($obj2, $obj1);
    }

    public function testIsSame(): void
    {
        $obj1 = new class(24) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };

        $obj2 = new class(42) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new class(42) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };

        $obj2 = new class(42) implements ValueObjectInterface
        {
            use ValueObjectTrait;
        };

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new ValueObjectTraitTestClass(12);
        $obj2 = new ValueObjectTraitTestClass(13);

        $this->assertFalse($obj1->isSame($obj2));
        $this->assertFalse($obj2->isSame($obj1));

        $obj1 = new ValueObjectTraitTestClass(13);
        $obj2 = new ValueObjectTraitTestClass(13);

        $this->assertTrue($obj1->isSame($obj2));
        $this->assertTrue($obj2->isSame($obj1));
    }
}

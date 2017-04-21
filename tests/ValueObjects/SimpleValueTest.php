<?php

namespace Runn\tests\ValueObjects\SimpleValue;

use Runn\ValueObjects\SimpleValue;

class testClass extends SimpleValue {}

class SimpleValueTest extends \PHPUnit_Framework_TestCase
{

    public function testEmptyContsruct()
    {
        $valueObject = new class extends SimpleValue {};
        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
    }

    public function testNullConstruct()
    {
        $valueObject = new class(null) extends SimpleValue {};
        $this->assertNull($valueObject->getValue());
        $this->assertNull($valueObject());
    }

    public function testConstructGetValue()
    {
        $valueObject = new class('foo') extends SimpleValue {};

        $this->assertSame('foo', $valueObject->getValue());
        $this->assertSame('foo', $valueObject());
    }

    public function testNewStatic()
    {
        $valueObject = testClass::new('foo');

        $this->assertInstanceOf(testClass::class, $valueObject);
        $this->assertSame('foo', $valueObject->getValue());
        $this->assertSame('foo', $valueObject());
    }

    public function testNewDynamic()
    {
        $valueObject1 = new class('foo') extends SimpleValue {};
        $valueObject2 = $valueObject1->new('bar');

        $this->assertEquals(get_class($valueObject2), get_class($valueObject1));
        $this->assertNotSame($valueObject2, $valueObject1);
        $this->assertNotEquals($valueObject2, $valueObject1);
    }

    public function testToString()
    {
        $valueObject = new class(42) extends SimpleValue {};
        $this->assertSame('42', (string)$valueObject);

        $valueObject = new class('foo') extends SimpleValue {};
        $this->assertSame('foo', (string)$valueObject);

        $valueObject = new class([1, 2, 3]) extends SimpleValue {};
        $this->assertSame('Array', (string)$valueObject);
    }

    public function testJson()
    {
        $valueObject = new class(42) extends SimpleValue {};
        $this->assertSame('42', json_encode($valueObject));

        $valueObject = new class('foo') extends SimpleValue {};
        $this->assertSame('"foo"', json_encode($valueObject));

        $valueObject = new class([1, 2, 3]) extends SimpleValue {};
        $this->assertSame('[1,2,3]', json_encode($valueObject));
    }

}
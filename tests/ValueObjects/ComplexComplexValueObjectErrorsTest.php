<?php

namespace Runn\tests\ValueObjects\ComplexValueObject;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\InvalidFloat;
use Runn\Validation\Exceptions\InvalidInt;
use Runn\Validation\Exceptions\InvalidString;
use Runn\ValueObjects\ComplexValueObject;
use Runn\ValueObjects\Errors\ComplexValueObjectErrors;
use Runn\ValueObjects\Errors\InvalidFieldValue;
use Runn\ValueObjects\Values\FloatValue;
use Runn\ValueObjects\Values\IntValue;
use Runn\ValueObjects\Values\StringValue;

class Inner1VO extends StringValue {};
class Inner2VO extends IntValue {};

class InnerVO extends ComplexValueObject {
    protected static $schema = [
        'first'  => ['class' => Inner1VO::class],
        'second' => ['class' => Inner2VO::class],
    ];
}

class TestVo extends ComplexValueObject {
    protected static $schema = [
        'foo' => ['class' => InnerVO::class]
    ];
}

class OuterVo extends ComplexValueObject {
    protected static $schema = [
        'bar' => ['class' => TestVo::class],
        'baz' => ['class' => FloatValue::class],
    ];
}


class ComplexComplexValueObjectErrorsTest extends TestCase
{

    public function testOneLevelErrors()
    {
        try {
            $obj = new InnerVO(['first' => [1, 2, 3], 'second' => new \stdClass()]);
        } catch (ComplexValueObjectErrors $errors) {

            $this->assertCount(2, $errors);

            $this->assertInstanceOf(InvalidFieldValue::class, $errors[0]);
            $this->assertInstanceOf(InvalidFieldValue::class, $errors[1]);

            $this->assertSame('first',  $errors[0]->getField());
            $this->assertInstanceOf(InvalidString::class,  $errors[0]->getPrevious());

            $this->assertSame('second', $errors[1]->getField());
            $this->assertInstanceOf(InvalidInt::class,  $errors[1]->getPrevious());

            return;
        }
        $this->fail();
    }

    public function testTwoLevelErrors()
    {
        try {
            $obj = new TestVo([
                'foo' => [
                    'first' => [1, 2, 3], 'second' => new \stdClass()
                ],
            ]);
        } catch (ComplexValueObjectErrors $errors) {

            $this->assertCount(2, $errors);

            $this->assertInstanceOf(InvalidFieldValue::class, $errors[0]);
            $this->assertInstanceOf(InvalidFieldValue::class, $errors[1]);

            $this->assertSame('foo.first',  $errors[0]->getField());
            $this->assertInstanceOf(InvalidString::class,  $errors[0]->getPrevious());

            $this->assertSame('foo.second', $errors[1]->getField());
            $this->assertInstanceOf(InvalidInt::class,  $errors[1]->getPrevious());

            return;
        }
        $this->fail();
    }

    public function testThreeLevelErrors()
    {
        try {
            $obj = new OuterVo([
                'bar' => [
                    'foo' => [
                        'first' => [1, 2, 3], 'second' => new \stdClass()
                    ],
                ],
                'baz' => 'blabla'
            ]);
        } catch (ComplexValueObjectErrors $errors) {

            $this->assertCount(3, $errors);

            $this->assertInstanceOf(InvalidFieldValue::class, $errors[0]);
            $this->assertInstanceOf(InvalidFieldValue::class, $errors[1]);
            $this->assertInstanceOf(InvalidFieldValue::class, $errors[2]);

            $this->assertSame('bar.foo.first',  $errors[0]->getField());
            $this->assertInstanceOf(InvalidString::class,  $errors[0]->getPrevious());

            $this->assertSame('bar.foo.second', $errors[1]->getField());
            $this->assertInstanceOf(InvalidInt::class,  $errors[1]->getPrevious());

            $this->assertSame('baz', $errors[2]->getField());
            $this->assertInstanceOf(InvalidFloat::class,  $errors[2]->getPrevious());

            return;
        }
        $this->fail();
    }

}

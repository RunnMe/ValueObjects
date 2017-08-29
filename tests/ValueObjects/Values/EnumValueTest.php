<?php

namespace Runn\tests\ValueObjects\Values\IntValue;

use Runn\ValueObjects\Values\EnumValue;

class EnumValueTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Runn\Validation\Exceptions\OutOfEnum
     */
    public function testEmptyValues1()
    {
        $value = new class extends EnumValue { };
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\OutOfEnum
     */
    public function testEmptyValues2()
    {
        $value = new class('foo') extends EnumValue { };
    }

    /**
     * @expectedException \Runn\Validation\Exceptions\OutOfEnum
     */
    public function testInvalid()
    {
        $value = new class('foo') extends EnumValue {
            const VALUES = ['bar', 'baz'];
        };
    }

    public function testValid()
    {
        $value = new class('foo') extends EnumValue {
            const VALUES = ['foo', 'bar'];
        };

        $this->assertInstanceOf(EnumValue::class, $value);
        $this->assertSame('foo', $value->getValue());
    }

}
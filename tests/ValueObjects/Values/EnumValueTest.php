<?php

namespace Runn\tests\ValueObjects\Values\IntValue;

use PHPUnit\Framework\TestCase;
use Runn\Validation\Exceptions\OutOfEnum;
use Runn\ValueObjects\Values\EnumValue;

class EnumValueTest extends TestCase
{

    public function testEmptyValues1()
    {
        $this->expectException(OutOfEnum::class);
        $value = new class extends EnumValue { };
    }

    public function testEmptyValues2()
    {
        $this->expectException(OutOfEnum::class);
        $value = new class('foo') extends EnumValue { };
    }

    public function testInvalid()
    {
        $this->expectException(OutOfEnum::class);
        $value = new class('foo') extends EnumValue {
            const VALUES = ['bar', 'baz'];
        };
    }

    public function testValid()
    {
        $value = new class('foo') extends EnumValue {
            public const VALUES = ['foo', 'bar'];
        };

        $this->assertInstanceOf(EnumValue::class, $value);
        $this->assertSame('foo', $value->getValue());
    }

}

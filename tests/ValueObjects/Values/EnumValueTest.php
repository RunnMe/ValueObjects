<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\EnumValue;

class EnumValueTest extends TestCase
{
    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\OutOfEnum
     */
    public function testEmptyValue($input): void
    {
        new class($input) extends EnumValue
        {
        };
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\OutOfEnum
     */
    public function testInvalidValue($input): void
    {
        new class($input) extends EnumValue {
            public const VALUES = ['bar', 'baz'];
        };
    }

    public function testValid(): void
    {
        $value = new class('foo') extends EnumValue {
            public const VALUES = ['foo', 'bar'];
        };

        $this->assertInstanceOf(EnumValue::class, $value);
        $this->assertSame('foo', $value->getValue());
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'empty' => [''],
            'null' => [null],
            'string' => ['foo'],
        ];
    }
}

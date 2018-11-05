<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\SingleValueObject;
use Runn\ValueObjects\Values\StringValue;

/**
 * Class StringValueTest
 * @package Tests\ValueObjects\Values
 */
class StringValueTest extends TestCase
{
    /**
     * @throws \Runn\Validation\ValidationError
     */
    public function testNull(): void
    {
        $valueObject = new StringValue(null);

        $this->assertInstanceOf(SingleValueObject::class, $valueObject);
        $this->assertInstanceOf(StringValue::class, $valueObject);

        $this->assertSame('', $valueObject->getValue());
        $this->assertSame('', $valueObject());
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider stringProvider
     * @throws \Runn\Validation\ValidationError
     */
    public function testString($input, $expected): void
    {
        $valueObject = new StringValue($input);

        $this->assertInternalType('string', $valueObject->getValue());
        $this->assertSame($expected, $valueObject->getValue());
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidString
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new StringValue($input);
    }

    /**
     * @return array
     */
    public function stringProvider(): array
    {
        return [
            'empty string is empty string' => ['', ''],
            'string is a string' => ['foo', 'foo'],
            'true is a empty string' => [false, ''],
            'false is a 1 in string' => [true, '1'],
            'object' => [
                new class
                {
                    public function __toString()
                    {
                        return 'foo';
                    }
                },
                'foo'
            ],
            'int #1' => [0, '0'],
            'int #2' => [42, '42'],
            'float #1' => [1.23, '1.23'],
            'float #2' => [1.2e34, '1.2E+34'],
        ];
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'array' => [[1, 2, 3]],
            'resource' => [fopen('php://input', 'rb')],
            'object' => [
                new class
                {
                    //
                }
            ],
        ];
    }
}

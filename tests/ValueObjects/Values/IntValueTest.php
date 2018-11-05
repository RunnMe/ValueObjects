<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\IntValue;

/**
 * Class IntValueTest
 * @package Tests\ValueObjects\Values
 */
class IntValueTest extends TestCase
{
    /**
     * @param $input
     * @param $expected
     * @throws \Runn\Validation\ValidationError
     * @dataProvider intProvider
     */
    public function testConstruct($input, $expected): void
    {
        $valueObject = new IntValue($input);

        $this->assertInternalType('integer', $valueObject->getValue());
        $this->assertSame($expected, $valueObject->getValue());
    }

    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\ValidationError
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new IntValue($input);
    }

    /**
     * @return array
     */
    public function intProvider(): array
    {
        return [
            'zero int' => [0, 0],
            'not zero int' => [42, 42],
            'int is a string' => ['42', 42],
        ];
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'null' => [null],
            'false' => [false],
            'true' => [true],
            'empty string' => [''],
            'float #1' => [1.23],
            'float #2' => [1.2e34],
            'array' => [[1, 2, 3]],
            'object' => [
                new class
                {
                    //
                }
            ],
            'resource' => [fopen('php://input', 'rb')],
            'more than int' => [PHP_INT_MAX + 1],
        ];
    }
}

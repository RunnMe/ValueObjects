<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\FloatValue;

/**
 * Class FloatValueTest
 * @package Tests\ValueObjects\Values
 */
class FloatValueTest extends TestCase
{
    /**
     * @param $input
     * @param $expected
     * @dataProvider floatProvider
     * @throws \Runn\Validation\ValidationError
     */
    public function testFloat($input, $expected): void
    {
        $valueObject = new FloatValue($input);

        $this->assertInternalType('float', $valueObject->getValue());
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
        new FloatValue($input);
    }

    /**
     * @return array
     */
    public function floatProvider(): array
    {
        return [
            'int #1' => [0, 0.0],
            'int #2' => [42, 42.0],
            'float #1' => [3.14159, 3.14159],
            'float #2' => [1.2e34, 1.2e34],
            'string' => ['3.14159', 3.14159]
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
            'not empty string' => ['blah-blah-blah'],
            'array' => [[1, 2, 3]],
            'object' => [
                new class
                {
                    //
                }
            ],
            'resource' => [fopen('php://input', 'rb')],
        ];
    }
}

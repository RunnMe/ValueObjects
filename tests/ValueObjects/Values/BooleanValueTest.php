<?php

namespace Runn\Tests\ValueObjects\Values;

use PHPUnit\Framework\TestCase;
use Runn\ValueObjects\Values\BooleanValue;

/**
 * Class BooleanValueTest
 * @package Tests\ValueObjects\Values
 */
class BooleanValueTest extends TestCase
{
    /**
     * @param $input
     * @dataProvider invalidValueProvider
     * @expectedException \Runn\Validation\Exceptions\InvalidBoolean
     * @throws \Runn\Validation\ValidationError
     */
    public function testInvalidValue($input): void
    {
        new BooleanValue($input);
    }

    /**
     * @param $input
     * @dataProvider falseProvider
     * @throws \Runn\Validation\ValidationError
     */
    public function testFalse($input): void
    {
        $valueObject = new BooleanValue($input);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertFalse($valueObject->getValue());
    }

    /**
     * @param $input
     * @dataProvider trueProvider
     * @throws \Runn\Validation\ValidationError
     */
    public function testTrue($input): void
    {
        $valueObject = new BooleanValue($input);
        $this->assertInternalType('bool', $valueObject->getValue());
        $this->assertTrue($valueObject->getValue());
    }

    /**
     * @return array
     */
    public function falseProvider(): array
    {
        return [
            'null' => [null],
            'false in a bool' => [false],
            'empty string' => [''],
            'false in a string' => ['false'],
            'false is the string "off"' => ['off'],
            'false is the string "no"' => ['no'],
            'false is zero in string' => ['0'],
            'false is zero' => [0],
        ];
    }

    /**
     * @return array
     */
    public function trueProvider(): array
    {
        return [
            'true in a bool' => [true],
            'true in a string' => ['true'],
            'true is the string "on"' => ['on'],
            'true is the string "yes"' => ['yes'],
            'true is the string "blah-blah-blah"' => ['blah-blah-blah'],
            'true is 1 in string' => ['1'],
            'true is 42 in string' => ['42'],
            'true is float in string' => ['3.14159'],
            'true is 1 in int' => [1],
            'true is 42 in int' => [42],
            'true is float in float' => [3.14159],
        ];
    }

    /**
     * @return array
     */
    public function invalidValueProvider(): array
    {
        return [
            'empty array' => [[]],
            'not empty array' => [[1, 2, 3]],
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
